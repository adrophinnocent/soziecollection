<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
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
     * The shop grid, quick view, cart drawer, wishlist drawer and related
     * products were all still painted with the pre-theme navy palette
     * (#081944 card, #061338 image frame, #B39A84 bronze text, #0A1E54 label on
     * bronze) while the rest of the storefront had moved to obsidian and gold.
     * Every one of those hexes is now denied outright, because a card is the
     * one thing a customer looks at first: if it drifts back to navy the page
     * reads as two different shops.
     *
     * @return array<string, string> hex => what it used to paint
     */
    public static function retiredNavyPalette(): array
    {
        return [
            '#081944' => 'the navy card surface',
            '#0A1E54' => 'the navy label on a bronze button and the navy discount badge',
            '#061338' => 'the navy image frame and the navy gradient banner',
            '#B39A84' => 'the bronze text, borders and buttons',
            '#917B68' => 'the bronze hover surface',
            '#F9F0EE' => 'the cool off-white heading text',
            '#CFC7C8' => 'the cool grey secondary text',
        ];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function navyPaletteProvider(): array
    {
        $cases = [];

        foreach (self::retiredNavyPalette() as $hex => $wasUsedFor) {
            $cases[$hex] = [$hex, $wasUsedFor];
        }

        return $cases;
    }

    /**
     * A hex is denied in every utility position it could be smuggled back in:
     * as a background, a border, or a text colour, on the storefront *and* in
     * the admin panel.
     */
    #[DataProvider('navyPaletteProvider')]
    public function test_the_retired_navy_palette_cannot_come_back(string $hex, string $wasUsedFor): void
    {
        foreach ($this->renderedStorefrontPages() as $url => $html) {
            foreach (['bg', 'border', 'text'] as $property) {
                $this->assertSame(
                    0,
                    preg_match(
                        sprintf('/(?<![\w:-])%s-\[%s\](?![\w\/-])/', preg_quote($property, '/'), preg_quote($hex, '/')),
                        $html,
                    ),
                    sprintf(
                        '%s on %s: %s is retired, the storefront is obsidian and gold (see resources/css/app.css).',
                        $property.'-['.$hex.']',
                        $url,
                        $wasUsedFor,
                    ),
                );
            }
        }

        $admin = User::factory()->superAdmin()->create();

        foreach (['/admin', '/admin/marketing'] as $url) {
            $this->assertSame(
                0,
                preg_match(
                    sprintf('/(?<![\w:-])(?:bg|border|text)-\[%s\](?![\w\/-])/', preg_quote($hex, '/')),
                    $this->actingAs($admin)->get($url)->assertOk()->getContent(),
                ),
                sprintf('%s on %s: the retired navy palette must not reappear in the admin panel either.', $hex, $url),
            );
        }
    }

    /**
     * The second light palette the storefront carried, and the reason the first
     * denylist let the regression through: #EDE5D8, #F8F5EF, #D8C9B8 and #29241F
     * were the tokens that were converted, so guarding them proved nothing about
     * this set. #F5F0E8 / #E8DED0 / #B99A5B / #8F6E3B / #5C5248 / #211E1A are a
     * *warmer, more saturated* cream-and-bronze ramp, they were never listed, and
     * the homepage hero, signature blends and final CTA were still painted with
     * them: a cream hero block inside an otherwise obsidian page.
     *
     * @return array<string, string> hex => what it used to paint
     */
    public static function retiredCreamPalette(): array
    {
        return [
            '#F5F0E8' => 'the page, hero and product-image-well surface',
            '#E8DED0' => 'the border, section divider and raised polygon frame',
            '#B99A5B' => 'the bronze accent, gold border, gold shadow and gold button fill',
            '#8F6E3B' => 'the dark bronze text and the bronze button fill',
            '#5C5248' => 'the body text on cream',
            '#211E1A' => 'the near-black fill, the heading text on cream and the scrim gradient',
        ];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function creamPaletteProvider(): array
    {
        $cases = [];

        foreach (self::retiredCreamPalette() as $hex => $wasUsedFor) {
            $cases[$hex] = [$hex, $wasUsedFor];
        }

        return $cases;
    }

    /**
     * Denied in every utility position it could be smuggled back in: as a
     * background, a border, or a text colour, on the storefront and in the admin.
     */
    #[DataProvider('creamPaletteProvider')]
    public function test_the_retired_cream_palette_cannot_come_back(string $hex, string $wasUsedFor): void
    {
        foreach ($this->renderedStorefrontPages() as $url => $html) {
            foreach (['bg', 'border', 'text'] as $property) {
                $this->assertSame(
                    0,
                    preg_match(
                        sprintf('/(?<![\w:-])%s-\[%s\](?![\w\/-])/', preg_quote($property, '/'), preg_quote($hex, '/')),
                        $this->withoutReTintedResultCount($html),
                    ),
                    sprintf(
                        '%s on %s: %s is retired, the storefront is obsidian and gold (see resources/css/app.css).',
                        $property.'-['.$hex.']',
                        $url,
                        $wasUsedFor,
                    ),
                );
            }
        }

        $admin = User::factory()->superAdmin()->create();

        foreach (['/admin', '/admin/marketing', '/admin/content', '/admin/products'] as $url) {
            $this->assertSame(
                0,
                preg_match(
                    sprintf('/(?<![\w:-])(?:bg|border|text)-\[%s\](?![\w\/-])/', preg_quote($hex, '/')),
                    $this->actingAs($admin)->get($url)->assertOk()->getContent(),
                ),
                sprintf('%s on %s: the retired cream palette must not reappear in the admin panel either.', $hex, $url),
            );
        }
    }

    /**
     * The "Showing N perfumes" count is a `<strong>` inside a translated string,
     * so its own `#8F6E3B` cannot be edited at the call site without rewriting
     * the translation. `html.sozie-storefront .sozie-result-count strong` in
     * app.css re-tints it to the gold that passes, so the utility itself is inert.
     * That container is the single sanctioned exception, and it is stripped here
     * so the rest of the document is still scanned — if the hook in app.css is ever
     * removed, the bronze paints again and this stops matching.
     */
    private function withoutReTintedResultCount(string $html): string
    {
        return preg_replace('#<div class="sozie-result-count.*?</div>#s', '', $html) ?? $html;
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
