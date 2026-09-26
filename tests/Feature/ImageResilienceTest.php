<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Regression guard for "the product pictures are missing on Chrome".
 *
 * Product photography is stored in the database as an absolute URL, so every
 * product card, the detail page, quick view, the wishlist and the order items
 * depend on that host being reachable. On a network that cannot reach it — which
 * is browser- and carrier-dependent, and was the reason the storefront looked
 * complete on one handset and empty on another — each of those images collapsed
 * to nothing.
 *
 * The rule enforced here: an image that is allowed to be missing is marked, and
 * the marked images are replaced by a bundled local placeholder when they fail.
 * A page must never point at a third-party image host of its own accord, so a
 * blocked or throttled host can never decide whether a card renders.
 */
class ImageResilienceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Marks the images the layout's window.sozieImageFallback() helper watches.
     * An <img> without it is expected to always load from this origin.
     */
    private const FALLBACK_MARKER = 'data-sozie-fallback';

    private const PLACEHOLDER = 'images/product-placeholder.svg';

    /** @return array<string, array{string}> */
    public static function pages(): array
    {
        return [
            'home' => ['/'],
            'shop' => ['/shop'],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // The seeded catalogue is what puts remote image URLs into the markup, so
        // it is the only way to prove the marker is applied to the real cards.
        $this->seed();
    }

    #[DataProvider('pages')]
    public function test_page_renders_at_least_one_image_marked_for_the_placeholder_fallback(string $url): void
    {
        $html = $this->htmlFor($url);

        $this->assertGreaterThan(
            0,
            preg_match_all('/<img\b[^>]*\b'.self::FALLBACK_MARKER.'\b/i', $html),
            "[{$url}] renders no image marked with ".self::FALLBACK_MARKER.', so a blocked image host would still leave a card with no photo.'
        );
    }

    #[DataProvider('pages')]
    public function test_page_loads_no_image_from_a_third_party_host_unless_it_can_fall_back(string $url): void
    {
        $html = $this->htmlFor($url);

        preg_match_all('/<img\b[^>]*>/i', $html, $images);

        $unprotected = [];

        foreach ($images[0] as $image) {
            if (preg_match('/\bsrc\s*=\s*(["\'])(.*?)\1/i', $image, $src) !== 1) {
                // A bound :src has no literal URL to judge until Alpine runs.
                continue;
            }

            if ($this->isSameOrigin($src[2]) || preg_match('/\b'.self::FALLBACK_MARKER.'\b/i', $image) === 1) {
                continue;
            }

            $unprotected[] = $image;
        }

        $this->assertSame(
            [],
            $unprotected,
            "[{$url}] loads an image from a third-party host that cannot fall back. Self-host it, or mark the <img> with ".self::FALLBACK_MARKER.'.'
        );
    }

    #[DataProvider('pages')]
    public function test_page_wires_the_guarded_fallback_to_a_placeholder_it_actually_serves(string $url): void
    {
        $html = $this->htmlFor($url);

        $this->assertIsInt(
            strpos($html, 'window.sozieImageFallback ='),
            "[{$url}] is missing the guarded image fallback helper."
        );

        // The helper has to exist before the deferred bundles boot Alpine, or an
        // image failing during the first paint would find no listener to catch it.
        $this->assertLessThan(
            strpos($html, asset('vendor/lucide.min.js')),
            strpos($html, 'window.sozieImageFallback ='),
            "[{$url}] registers the image fallback after the deferred bundle, so it misses images that fail during the first paint."
        );

        // Blade's @js() JSON-encodes the URL, which escapes its slashes.
        $this->assertStringContainsString(
            asset(self::PLACEHOLDER),
            str_replace('\/', '/', $html),
            "[{$url}] does not point the fallback at ".asset(self::PLACEHOLDER).'.'
        );

        // It has to be a committed file under public/, otherwise the web server has
        // nothing to hand back and the fallback fails exactly when it is needed.
        $this->assertFileExists(public_path(self::PLACEHOLDER));
    }

    public function test_the_bundled_placeholder_is_committed_and_carries_the_brand(): void
    {
        $this->assertFileExists(public_path(self::PLACEHOLDER));

        $svg = (string) file_get_contents(public_path(self::PLACEHOLDER));

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);

        // Warm sand, taupe, espresso and the champagne gold of the bottle glyph.
        $this->assertStringContainsString('#EDE5D8', $svg);
        $this->assertStringContainsString('#D8C9B8', $svg);
        $this->assertStringContainsString('#29241F', $svg);
        $this->assertStringContainsString('#A8895F', $svg);
    }

    public function test_no_view_file_hardcodes_a_third_party_image_url(): void
    {
        $offenders = [];

        // Any absolute URL an <img> or a bound :src/JS slide reads. The homepage
        // fallback slides and the gallery are plain JS strings, so a view can
        // name a remote image without ever writing an <img> tag.
        $imageUrls = [
            '/<img\b[^>]*\bsrc\s*=\s*(["\'])(https?:\/\/.*?)\1/i',
            '/\b(?:srcset|image)\s*[:=]\s*(["\'])(https?:\/\/.*?)\1/i',
        ];

        foreach ($this->bladeViewFiles() as $file) {
            $source = (string) file_get_contents($file);

            if (str_contains($source, 'images.unsplash.com')) {
                $offenders[] = $file.' hardcodes images.unsplash.com';
            }

            foreach ($imageUrls as $pattern) {
                preg_match_all($pattern, $source, $matches);

                foreach ($matches[2] ?? [] as $url) {
                    if (! $this->isSameOrigin($url)) {
                        $offenders[] = $file.' reads an image from '.$url;
                    }
                }
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "These views still point a browser at a third-party image host:\n  ".implode("\n  ", $offenders)
        );
    }

    /**
     * @return list<string>
     */
    private function bladeViewFiles(): array
    {
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(resource_path('views'), \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = str_replace('\\', '/', $file->getPathname());
            }
        }

        sort($files);

        return $files;
    }

    private function htmlFor(string $url): string
    {
        return $this->get($url)->assertOk()->getContent();
    }

    private function isSameOrigin(string $url): bool
    {
        if (str_starts_with($url, '//')) {
            return parse_url('https:'.$url, PHP_URL_HOST) === parse_url(asset('/'), PHP_URL_HOST);
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            // A relative or scheme URL is resolved against this document, so it
            // can never leave the origin.
            return true;
        }

        // Compared against the origin asset() itself resolves to, so this stays
        // correct whatever APP_URL a given environment happens to carry.
        return parse_url($url, PHP_URL_HOST) === parse_url(asset('/'), PHP_URL_HOST);
    }
}
