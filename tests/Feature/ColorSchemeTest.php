<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColorSchemeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seeded, because the palette guards below are only meaningful against the
     * markup that actually renders: product cards, the cart row and the order
     * summary never appear against an empty catalogue, so without a seed the
     * corresponding assertions would pass no matter what the views contained.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * Every storefront page, rendered. `/cart` and `/checkout` redirect to the
     * shop when the cart is empty, and the cart row plus the order summary only
     * exist once something is in the cart — so the cart is primed first,
     * otherwise these guards would never see the markup they are meant to guard.
     *
     * @return array<string, string> url => rendered html
     */
    private function renderedStorefrontPages(): array
    {
        $this->postJson('/cart/add', [
            'product_id' => Product::first()->id,
            'size' => '50ml Signature Bottle',
            'quantity' => 2,
        ])->assertOk();

        $pages = [];
        foreach ([
            '/',
            '/shop',
            '/product/'.Product::first()->slug,
            '/cart',
            '/checkout',
            '/login',
            '/register',
            '/track-order',
            '/forgot-password',
        ] as $url) {
            $pages[$url] = $this->get($url)->assertOk()->getContent();
        }

        return $pages;
    }

    /**
     * The storefront used to be a light warm-sand document that browsers were
     * free to force-dark or auto-invert. It is now a genuine dark theme, and
     * these are the two declarations that make the user agent respect it.
     */
    public function test_storefront_pins_the_document_to_a_dark_colour_scheme(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<meta name="color-scheme" content="dark">', false);
        $response->assertSee('<meta name="theme-color" content="#0C0A09">', false);
        $response->assertDontSee('<meta name="color-scheme" content="light">', false);
    }

    public function test_storefront_header_uses_the_dark_obsidian_panel(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $header = $this->headerClasses($response->getContent());

        $this->assertStringContainsString('glass-panel-dark', $header);
        $this->assertStringNotContainsString('glass-panel ', $header);
        $this->assertStringNotContainsString('bg-[#F8F5EF]', $header);
        $this->assertStringNotContainsString('border-[#D8C9B8]', $header);
    }

    public function test_admin_pages_pin_the_document_to_a_light_colour_scheme(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSee('<meta name="color-scheme" content="light">', false);
    }

    /**
     * The dark theme has to be a real design in its own right, not something the
     * browser infers: the layout must no longer hand it the old warm-sand page
     * and ivory card surfaces to restyle. #EDE5D8, #F8F5EF and #D8C9B8 may only
     * survive as light *text* (#EDE5D8 becomes the body colour), never as a
     * background or a border.
     *
     * Each pattern is boundary-anchored, because the deliberate `hover:bg-white`
     * on the final CTA and the `bg-white/90` chip over product photography are
     * light-on-hover surfaces, not resting surfaces.
     */
    public function test_storefront_drops_the_legacy_light_surfaces(): void
    {
        foreach ($this->renderedStorefrontPages() as $url => $html) {
            $this->assertNoLegacyUtility($html, 'bg', '#EDE5D8', "bg-[#EDE5D8] on {$url}: the page/section surface is #0C0A09 on the dark theme.");
            $this->assertNoLegacyUtility($html, 'bg', '#F8F5EF', "bg-[#F8F5EF] on {$url}: the card/panel surface is #17130F on the dark theme.");
            $this->assertNoLegacyUtility($html, 'border', '#D8C9B8', "border-[#D8C9B8] on {$url}: the subtle border is #322B23 on the dark theme.");
            $this->assertNoLegacyUtility($html, 'text', '#29241F', "text-[#29241F] on {$url}: primary text is #EDE5D8, not espresso, on the dark theme.");

            $this->assertSame(
                0,
                preg_match('/(?<![\w-])bg-white(?![\w\/-])/', $html),
                "bg-white on {$url}: cards are #17130F, not white, on the dark theme.",
            );
        }
    }

    /**
     * The published Laravel paginator is the one piece of storefront markup that
     * does not live in an application view, and the stock copy paints itself with
     * Tailwind's light palette. It has to be re-cut too, or the shop grid gets a
     * row of white pills in the middle of a black page.
     */
    public function test_pagination_is_part_of_the_dark_theme(): void
    {
        $this->assertSame(
            0,
            preg_match('/(?<![\w-])bg-white(?![\w\/-])/', $this->get('/shop')->assertOk()->getContent()),
            'The shop paginator is still painting itself with Tailwind\'s light palette.',
        );
    }

    /**
     * A gold-filled control with a white label is 3.28:1 and fails WCAG AA; the
     * near-black label is 5.79:1 and passes. This guards the rule the whole
     * storefront is built on, so a future edit cannot quietly reintroduce
     * white-on-gold.
     */
    public function test_gold_filled_buttons_use_a_near_black_label(): void
    {
        foreach ($this->renderedStorefrontPages() as $url => $html) {
            $this->assertSame(
                0,
                preg_match('/(?<![\w-])bg-\[#A8895F\][^"\']*\btext-white\b/', $html),
                "A gold-filled control on {$url} still has a white label.",
            );
        }
    }

    /**
     * The dark palette only reaches the storefront because the layout opts into
     * it by class: that hook is what scopes the obsidian :root/body/input rules
     * in app.css away from the still-light admin panel.
     */
    public function test_storefront_layout_opts_into_the_obsidian_palette(): void
    {
        $this->assertStringContainsString(
            'class="sozie-storefront scroll-smooth"',
            $this->get('/')->assertOk()->getContent(),
        );
    }

    /**
     * Asserts the utility `<property>-[<hex>]` does not appear as a *resting*
     * surface anywhere in the markup.
     *
     * The lookbehind/lookahead matter. `hover:bg-[#F8F5EF]` (the final CTA
     * flipping to ivory), `hover:bg-white` and `bg-white/90` (a light disc over
     * product photography) are deliberate light-on-hover surfaces — a user does
     * see them, and they are the intended inversion. A *resting* light surface
     * is what this rejects, because that is the pale box in a black page.
     */
    private function assertNoLegacyUtility(string $html, string $property, string $hex, string $because): void
    {
        $this->assertSame(
            0,
            preg_match(
                sprintf('/(?<![\w:-])%s-\[%s\](?![\w\/-])/', preg_quote($property, '/'), preg_quote($hex, '/')),
                $html,
            ),
            $because,
        );
    }

    private function headerClasses(string $html): string
    {
        $this->assertSame(1, preg_match('/<header[^>]*>/', $html, $matches), 'Expected exactly one <header> element.');

        return $matches[0];
    }
}
