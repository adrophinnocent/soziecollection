<?php

namespace Tests\Feature;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Regression guard for "the homepage is empty before I upload a slide".
 *
 * Homepage imagery comes only from slide designs the owner uploads through
 * Admin -> Store Setup -> Website Content. Nothing photographic ships with the
 * storefront any more, so the very first thing a brand-new site has to render is
 * the unconfigured homepage — and it has to look like a designed page, not an
 * empty frame: the default headline, the real description, both calls to action
 * and their destinations, the brand's own warm-sand and gold treatment, and no
 * gallery shell with nothing in it.
 *
 * The two ways that used to break, and are guarded here:
 *   - every hero binding read `slides[activeSlide]`, so with no slides the text
 *     went blank, the secondary call to action was hidden and the links lost
 *     their href;
 *   - the frames and the gallery read bundled stock photographs, so the page
 *     looked designed with third-party photos nobody had chosen.
 */
class HomepageDefaultHeroTest extends TestCase
{
    use RefreshDatabase;

    /** The gallery section's mount point: present only when there is a design to show. */
    private const GALLERY_MOUNT = 'x-data="gallerySlider(';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_homepage_renders_a_complete_default_hero_with_no_banner_rows(): void
    {
        $this->assertSame(0, Banner::count(), 'This test is about the state where the owner has uploaded no slide at all.');

        $response = $this->homepage();

        // The default hero, in full: headline, description and both calls to action.
        $response->assertSee('YOUR SCENT.')
            ->assertSee('YOUR SIGNATURE.')
            ->assertSee(__('Hero Description'))
            ->assertSee('EXPLORE COLLECTION')
            ->assertSee('FIND YOUR SCENT');

        $this->assertSame(
            2,
            preg_match_all('/<a\b[^>]*:href="currentSlide\.(?:secondary_)?button_link"/', $response->getContent()),
            'The hero must render two linked calls to action when no slide is configured.'
        );

        // The destinations themselves: the collection, and the scent finder further
        // down the page. Both are quoted by @js(), so accept either quote style.
        $script = $this->scriptBlock($response->getContent());

        $this->assertMatchesRegularExpression(
            '/button_link:\s*[\'"]'.preg_quote(route('shop.index'), '/').'[\'"]/',
            $script,
            'The default hero call to action must still point at the collection.'
        );

        $this->assertMatchesRegularExpression(
            '/secondary_button_link:\s*[\'"]#scent-finder[\'"]/',
            $script,
            'The default hero secondary call to action must still point at the scent finder.'
        );
    }

    public function test_homepage_ships_no_image_without_a_source_when_no_slide_has_a_design(): void
    {
        $this->assertSame(
            [],
            $this->imagesWithoutASource($this->homepage()->getContent()),
            'An <img> with no src is complete-but-zero-width, i.e. a broken image rather than a fallback. '
            .'The hero frame, the campaign frame and the experience frame all swap in the brand plate instead.'
        );
    }

    public function test_homepage_omits_the_gallery_with_zero_banners(): void
    {
        $html = $this->homepage()->getContent();

        $this->assertStringNotContainsString('#SOZIECOLLECTION GALLERY', $html, 'The gallery heading rendered with no slides.');
        $this->assertStringNotContainsString(__('Shop Scent'), $html, 'A gallery card rendered with no slides.');
        $this->assertStringNotContainsString(self::GALLERY_MOUNT, $html, 'The gallery component was mounted with no slides.');
    }

    public function test_a_slide_with_copy_but_no_design_does_not_mount_the_gallery(): void
    {
        Banner::create([
            'title' => 'Copy only slide',
            'eyebrow' => 'CAMPAIGN',
            'headline' => 'HEADLINE WITHOUT A DESIGN',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $html = $this->homepage()->getContent();

        $this->assertStringContainsString('HEADLINE WITHOUT A DESIGN', $html, 'The slide copy should still drive the hero.');
        $this->assertStringNotContainsString('#SOZIECOLLECTION GALLERY', $html, 'A slide with no design cannot fill a gallery card.');
        $this->assertStringNotContainsString(self::GALLERY_MOUNT, $html);
    }

    public function test_homepage_gallery_is_built_from_the_configured_slide_designs(): void
    {
        $slides = [
            $this->slide('First design', 'banners/first.jpg', 'FIRST EYEBROW', 'FIRST HEADLINE', 'First highlight.', 'First description.'),
            $this->slide('Second design', 'banners/second.jpg', 'SECOND EYEBROW', 'SECOND HEADLINE', 'Second highlight.', 'Second description.'),
            $this->slide('Third design', 'banners/third.jpg', 'THIRD EYEBROW', 'THIRD HEADLINE', 'Third highlight.', 'Third description.'),
        ];

        foreach ($slides as $slide) {
            Banner::create($slide);
        }

        $response = $this->homepage();

        $response->assertSee('#SOZIECOLLECTION GALLERY')->assertSee(__('Shop Scent'));

        $payload = $this->galleryPayload($response->getContent());

        $this->assertSame(3, substr_count($payload, 'headline'), 'The gallery must get one card per configured slide design.');

        // The owner's own photography and their own words — nothing invented, and
        // nothing bundled in its place.
        foreach ($slides as $slide) {
            $this->assertStringContainsString(basename($slide['image']), $payload);
            $this->assertStringContainsString($slide['eyebrow'], $payload);
            $this->assertStringContainsString($slide['headline'], $payload);
            $this->assertStringContainsString($slide['highlight_text'], $payload);
            $this->assertStringContainsString($slide['subtitle'], $payload);
        }

        $this->assertSame([], $this->imagesWithoutASource($response->getContent()));
    }

    public function test_a_single_configured_slide_renders_without_dead_navigation(): void
    {
        Banner::create($this->slide('Only design', 'banners/only.jpg', 'ONLY EYEBROW', 'ONLY HEADLINE', 'Only highlight.', 'Only description.'));

        $response = $this->homepage();

        $response->assertSee('ONLY HEADLINE', false)->assertSee('#SOZIECOLLECTION GALLERY');

        $this->assertSame(1, substr_count($this->galleryPayload($response->getContent()), 'headline'));

        // One slide is not a slideshow: the hero indicators and the gallery arrows
        // and dots are all behind a guard, so nothing is left paged through nothing.
        $this->assertStringContainsString('x-if="slides.length > 1"', $response->getContent());
        $this->assertStringContainsString('x-if="canPage"', $response->getContent());
    }

    /**
     * @return array<string, mixed>
     */
    private function slide(string $title, string $image, string $eyebrow, string $headline, string $highlight, string $subtitle): array
    {
        return [
            'title' => $title,
            'eyebrow' => $eyebrow,
            'headline' => $headline,
            'highlight_text' => $highlight,
            'subtitle' => $subtitle,
            'image' => $image,
            'is_active' => true,
            'sort_order' => 1,
        ];
    }

    private function homepage(): TestResponse
    {
        return $this->get('/')->assertOk();
    }

    /**
     * The slide payload the gallery slider is actually mounted with.
     *
     * Js::from() deliberately emits a quoted-for-HTML JSON dialect (every `"` is
     * written as \u0022), so this is read as text rather than decoded: what matters
     * is that the carousel was handed the owner's designs and the owner's words,
     * and nothing else.
     */
    private function galleryPayload(string $html): string
    {
        $this->assertSame(
            1,
            preg_match(
                '/'.preg_quote(self::GALLERY_MOUNT, '/').'JSON\.parse\(\'(.*?)\'\)\)"\s*\n\s*x-init="initSlider\(\)"/s',
                $html,
                $matches
            ),
            'The gallery section is not mounted, or is mounted without a slide payload.'
        );

        return $matches[1];
    }

    /**
     * The page's own inline <script> block, with the JSON escaping undone so a
     * quoted URL can be compared as a URL.
     */
    private function scriptBlock(string $html): string
    {
        return str_replace(['\\/', '\\\\/'], '/', $html);
    }

    /**
     * Every <img> the document ships must have something to load.
     *
     * @return list<string>
     */
    private function imagesWithoutASource(string $html): array
    {
        preg_match_all('/<img\b([^>]*)>/i', $html, $images);

        $offenders = [];

        foreach ($images[1] as $attributes) {
            // A bound :src or x-bind:src has no literal URL to judge until Alpine runs.
            if (preg_match('/\b(?:src|:src|x-bind:src)\s*=\s*("|")(.*?)\1/i', $attributes, $source) !== 1) {
                continue;
            }

            if (trim($source[2]) === '') {
                $offenders[] = '<img'.$attributes.'>';
            }
        }

        return $offenders;
    }
}
