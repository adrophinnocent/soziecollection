<?php

namespace Tests\Feature;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageSlidesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_the_command_writes_four_live_slides_and_their_artwork(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();

        $slides = Banner::query()->active()->ordered()->get();

        $this->assertCount(4, $slides);
        $this->assertSame([1, 2, 3, 4], $slides->pluck('sort_order')->all());

        foreach ($slides as $slide) {
            $this->assertNotEmpty($slide->eyebrow);
            $this->assertNotEmpty($slide->headline);
            $this->assertNotEmpty($slide->highlight_text);
            $this->assertNotEmpty($slide->subtitle);
            $this->assertNotEmpty($slide->button_text);
            $this->assertNotEmpty($slide->button_link);
            $this->assertNotEmpty($slide->secondary_button_text);
            $this->assertNotEmpty($slide->secondary_button_link);
        }
    }

    public function test_the_command_is_idempotent(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();
        $this->artisan('storefront:slides')->assertSuccessful();

        $this->assertSame(4, Banner::query()->count());
    }

    public function test_the_fresh_option_replaces_the_managed_slides(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();
        $this->artisan('storefront:slides --fresh')->assertSuccessful();

        $this->assertSame(4, Banner::query()->count());
    }

    public function test_every_slide_artwork_is_written_to_the_public_disk(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();

        foreach (Banner::query()->get() as $slide) {
            $this->assertStringStartsWith('banners/', (string) $slide->image);
            Storage::disk('public')->assertExists($slide->image);
        }
    }

    public function test_the_artwork_is_valid_svg_with_no_scripts_or_external_references(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();

        foreach (Banner::query()->get() as $slide) {
            $svg = Storage::disk('public')->get($slide->image);

            $this->assertIsString($svg);
            $this->assertStringStartsWith('<svg', trim($svg));
            $this->assertStringNotContainsStringIgnoringCase('<script', $svg);
            $this->assertStringNotContainsStringIgnoringCase('<image', $svg);
            $this->assertStringNotContainsStringIgnoringCase('javascript:', $svg);
            $this->assertStringNotContainsStringIgnoringCase('@import', $svg);
            $this->assertStringNotContainsStringIgnoringCase('href', $svg);

            // Every paint server and clip reference must be a local fragment id.
            preg_match_all('/url\(([^)]*)\)/', $svg, $references);

            foreach ($references[1] as $reference) {
                $this->assertStringStartsWith('#', $reference, "Slide artwork [{$slide->image}] references [{$reference}].");
            }

            $previous = libxml_use_internal_errors(true);
            $this->assertNotFalse(simplexml_load_string($svg), "Slide artwork [{$slide->image}] is not valid XML.");
            libxml_use_internal_errors($previous);
        }
    }

    public function test_slide_buttons_point_at_real_routes_or_home_anchors(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();

        $routes = $this->app['router']->getRoutes();

        foreach (Banner::query()->get() as $slide) {
            foreach ([$slide->button_link, $slide->secondary_button_link] as $link) {
                $this->assertIsString($link);
                $this->assertStringStartsWith('/', $link, "Slide [{$slide->title}] link [{$link}] must stay same-site.");

                $path = explode('#', $link)[0];

                if ($path === '') {
                    $this->assertStringStartsWith('/#', $link);

                    continue;
                }

                $this->assertNotNull(
                    $routes->match(Request::create($path, 'GET')),
                    "Slide [{$slide->title}] link [{$link}] does not resolve to a route."
                );
            }
        }
    }

    public function test_the_homepage_renders_the_generated_slides(): void
    {
        $this->artisan('storefront:slides')->assertSuccessful();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('SOZIE GOLDEN');
        $response->assertSee('AURA.');
        $response->assertSee('KIKOMO CHA MIPAKA');
        $response->assertSee('banners/sozie-golden-aura.svg');
    }
}
