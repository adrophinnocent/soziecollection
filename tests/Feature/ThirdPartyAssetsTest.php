<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Regression guard for "Chrome is missing features but Samsung Internet is fine".
 *
 * Every icon in the storefront is Lucide, and it used to be pulled from the
 * unpkg.com CDN. When that one request was blocked or throttled, `window.lucide`
 * stayed undefined, so every `<i data-lucide>` rendered as an empty inline box —
 * search, heart, cart, hamburger, WhatsApp and every section icon vanished — and
 * the unguarded `lucide.createIcons()` calls threw inside Alpine's `x-init`,
 * taking the cart count, the wishlist and the drawer wiring down with them.
 *
 * The rule enforced here: the storefront fetches scripts, styles and fonts from
 * its own origin only. Nothing a browser can block or throttle gets to decide
 * whether a feature works.
 */
class ThirdPartyAssetsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Elements the browser fetches by itself, mapped to the attributes that
     * carry the URL.
     *
     * `<a href>` and `<img src>` are deliberately absent. An `<a>` is a
     * destination the visitor clicks — the WhatsApp concierge and the social
     * links have to keep working — and product photography lives in the database
     * as an absolute URL, so neither is a build-time asset.
     */
    private const SUBRESOURCE_ATTRIBUTES = [
        'script' => ['src'],
        'link' => ['href'],
        'iframe' => ['src'],
        'source' => ['src', 'srcset'],
        'video' => ['src', 'poster'],
        'audio' => ['src'],
        'embed' => ['src'],
        'object' => ['data'],
        'track' => ['src'],
    ];

    private const IGNORED_URL_PREFIXES = ['//', '#', 'data:', 'mailto:', 'tel:', 'javascript:'];

    /** @return array<string, array{string}> */
    public static function pages(): array
    {
        return [
            'home' => ['/'],
            'shop' => ['/shop'],
            'login' => ['/login'],
            'admin login' => ['/admin/login'],
        ];
    }

    #[DataProvider('pages')]
    public function test_page_fetches_no_subresource_from_a_third_party_host(string $url): void
    {
        $offSite = $this->thirdPartySubresources($this->htmlFor($url));

        $this->assertSame(
            [],
            $offSite,
            "[{$url}] fetches a subresource from a third-party host. Self-host it (public/) and load it with asset()."
        );
    }

    #[DataProvider('pages')]
    public function test_every_script_tag_is_a_relative_or_same_origin_asset_url(string $url): void
    {
        $html = $this->htmlFor($url);

        preg_match_all('/<script\b[^>]*\bsrc\s*=\s*(["\'])(.*?)\1/i', $html, $matches);

        $this->assertNotEmpty($matches[2], "[{$url}] is expected to load at least one script.");

        foreach ($matches[2] as $src) {
            $this->assertTrue(
                $this->isSameOrigin($src),
                "[{$url}] loads the script [{$src}]. It must be a relative or same-origin asset() URL."
            );
        }
    }

    #[DataProvider('pages')]
    public function test_icons_come_from_the_self_hosted_build_and_cannot_break_the_page(string $url): void
    {
        $html = $this->htmlFor($url);
        $iconScript = asset('vendor/lucide.min.js');

        $this->assertStringContainsString($iconScript, $html);

        // Deferred, and unable to throw on the way in.
        $this->assertMatchesRegularExpression(
            '/<script[^>]+src="'.preg_quote($iconScript, '/').'"[^>]*\bdefer\b/i',
            $html
        );

        // The guarded renderer has to exist before anything — Alpine included —
        // is allowed to call it.
        $this->assertIsInt(strpos($html, 'window.sozieIcons ='), "{$url} is missing the guarded icon helper.");
        $this->assertLessThan(strpos($html, $iconScript), strpos($html, 'window.sozieIcons ='));

        // The raw renderer must be called from exactly one place: inside the
        // guard. A throw at any other call site is what took the cart, the
        // wishlist and the drawers down in the first place.
        $this->assertSame(
            1,
            substr_count($html, 'lucide.createIcons();'),
            "[{$url}] calls lucide.createIcons() outside the guarded window.sozieIcons() helper."
        );
    }

    public function test_the_committed_icon_build_is_a_real_umd_bundle(): void
    {
        $path = public_path('vendor/lucide.min.js');

        $this->assertFileExists($path);

        $source = file_get_contents($path);

        $this->assertGreaterThan(100_000, strlen($source), 'The icon build looks like an error page, not Lucide.');
        $this->assertStringContainsString('lucide v1.48.0', $source);
        $this->assertStringContainsString('createIcons', $source);
    }

    public function test_every_icon_name_the_views_ask_for_is_shipped_in_the_self_hosted_build(): void
    {
        $bundle = (string) file_get_contents(public_path('vendor/lucide.min.js'));

        // createIcons() resolves a data-lucide value as icons[PascalCase(value)] and,
        // on a miss, only logs a warning and leaves the <i> element sitting there. It
        // never throws and never retries, so a name the build does not ship produces
        // a permanently blank icon that looks exactly like a timing bug.
        $this->assertSame(
            1,
            preg_match('/Object\.freeze\(\{__proto__:null,([^}]*)\}/', $bundle, $iconSet),
            'Could not read the icon set out of the committed build.'
        );

        preg_match_all('/([A-Z][A-Za-z0-9]*):/', $iconSet[1], $shipped);
        $shipped = array_flip($shipped[1]);

        $missing = [];

        foreach ($this->bladeViewFiles() as $file) {
            $source = (string) file_get_contents($file);

            // Literal data-lucide values, plus the icon names the order tracker and
            // the account timeline hand to data-lucide from PHP arrays.
            preg_match_all('/data-lucide="([a-z0-9-]+)"|\'icon\'\s*=>\s*\'([a-z0-9-]+)\'/', $source, $names, PREG_SET_ORDER);

            foreach ($names as $name) {
                $asked = $name[1] !== '' ? $name[1] : $name[2];

                if (! array_key_exists($this->pascalCase($asked), $shipped)) {
                    $missing[] = basename($file).' asks for "'.$asked.'"';
                }
            }
        }

        $this->assertSame(
            [],
            $missing,
            "These icon names are not in the committed build, so createIcons() leaves an empty <i> where each one is used:\n  ".implode("\n  ", array_unique($missing))
            ."\nEither ship the icon, or inline the glyph the way the footer social links do."
        );
    }

    /**
     * Mirrors Lucide's own name lookup, so the test asks the same question
     * createIcons() asks rather than a stricter one.
     */
    private function pascalCase(string $name): string
    {
        $camel = '';
        $capitaliseNext = false;

        foreach (str_split($name) as $character) {
            if ($character === '-' || $character === '_' || $character <= ' ') {
                $capitaliseNext = $camel !== '';

                continue;
            }

            $camel .= $camel === '' ? strtolower($character) : ($capitaliseNext ? strtoupper($character) : $character);
            $capitaliseNext = false;
        }

        return ucfirst($camel);
    }

    /**
     * @return list<string>
     */
    private function bladeViewFiles(): array
    {
        $files = [];

        $views = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(resource_path('views'), \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($views as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        sort($files);

        return $files;
    }

    private function htmlFor(string $url): string
    {
        return $this->get($url)->assertOk()->getContent();
    }

    /**
     * @return list<string> Offending "src" and "href" values, for a readable failure message.
     */
    private function thirdPartySubresources(string $html): array
    {
        $tags = implode('|', array_keys(self::SUBRESOURCE_ATTRIBUTES));

        preg_match_all('/<('.$tags.')\b([^>]*)>/i', $html, $elements, PREG_SET_ORDER);

        $offSite = [];

        foreach ($elements as [, $tag, $attributeString]) {
            preg_match_all('/([a-z-]+)\s*=\s*(["\'])(.*?)\2/i', $attributeString, $attributes, PREG_SET_ORDER);

            foreach (self::SUBRESOURCE_ATTRIBUTES[strtolower($tag)] ?? [] as $attribute) {
                foreach ($attributes as [, $name, , $value]) {
                    if (strtolower($name) === $attribute && ! $this->isSameOrigin($value)) {
                        $offSite[] = "<{$tag} {$attribute}=\"{$value}\">";
                    }
                }
            }
        }

        return $offSite;
    }

    private function isSameOrigin(string $url): bool
    {
        foreach (self::IGNORED_URL_PREFIXES as $prefix) {
            if (str_starts_with($url, $prefix)) {
                return true;
            }
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            // Compared against the origin asset() itself resolves to, so this stays
            // correct whatever APP_URL a given environment happens to carry.
            return parse_url($url, PHP_URL_HOST) === parse_url(asset('/'), PHP_URL_HOST);
        }

        return true;
    }
}
