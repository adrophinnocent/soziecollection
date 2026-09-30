<?php

namespace Tests\Feature;

use App\Models\Product;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * The owner asked for three things about a product card: the details stacked
 * tidily in a fixed order, the price on the right-hand side of the card, and
 * the two-column mobile grid still readable. Those are layout facts, so they
 * are asserted against the parsed DOM rather than by grepping for a colour.
 */
class ProductCardLayoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function cardPages(): array
    {
        return [
            'shop grid' => ['/shop', 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6'],
            'homepage most loved' => ['/', 'grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6'],
        ];
    }

    /**
     * Every page that renders a product card: the catalogue grid, both homepage
     * grids and the related products grid on a product page.
     *
     * @return array<int, string>
     */
    private function cardPageUrls(): array
    {
        return [
            '/shop',
            '/',
            route('shop.show', Product::query()->firstOrFail()->slug),
        ];
    }

    public function test_the_card_puts_the_price_in_the_right_aligned_footer_row(): void
    {
        foreach ($this->cardPageUrls() as $url) {
            $xpath = $this->xpathFor($url);

            $rows = $xpath->query('//*[@data-card-price-row]');

            $this->assertGreaterThan(0, $rows->length, "No card on {$url} rendered a price row.");

            foreach ($rows as $row) {
                /** @var DOMElement $row */
                $price = $xpath->query('.//*[@data-card-price]', $row)->item(0);

                $this->assertInstanceOf(DOMElement::class, $price, "A price row on {$url} has no price in it.");
                $this->assertStringContainsString(
                    'justify-end',
                    $row->getAttribute('class'),
                    "The price row on {$url} is not right-aligned, so the price is not on the right of the card.",
                );
                $this->assertMatchesRegularExpression(
                    '/^TZS\s[\d,]+$/',
                    trim($price->textContent),
                    'The price must be the current price, not a bare number.',
                );
            }
        }
    }

    /**
     * The owner's complaint was dead space: the price used to sit under the
     * title on the left with the notes stranded above it. The order is now
     * pinned: image, category tag, name, notes, divider, price row, button.
     */
    public function test_the_card_stacks_its_details_in_the_agreed_order(): void
    {
        foreach ($this->cardPageUrls() as $url) {
            $xpath = $this->xpathFor($url);
            $priceRows = $xpath->query('//*[@data-card-price-row]');
            $checked = 0;

            foreach ($priceRows as $priceRow) {
                /** @var DOMElement $priceRow */
                $card = $xpath->query('ancestor::div[contains(@class, "navy-card")][1]', $priceRow)->item(0);

                if (! $card instanceof DOMElement) {
                    continue;
                }

                $checked++;
                $landmarks = [
                    'image frame' => $xpath->query('.//div[contains(@class, "aspect-square")][1]', $card)->item(0),
                    'product name' => $xpath->query('.//h3 | .//h4', $card)->item(0),
                    'notes' => $xpath->query('.//p[1]', $card)->item(0),
                    'divider footer' => $xpath->query('ancestor::div[contains(@class, "border-t")][1]', $priceRow)->item(0),
                    'price row' => $priceRow,
                    'add to cart' => $xpath->query('.//button[.//span[contains(., "ADD TO CART")]]', $card)->item(0),
                ];

                $previous = -1;

                foreach ($landmarks as $label => $landmark) {
                    $this->assertInstanceOf(
                        DOMElement::class,
                        $landmark,
                        "A card on {$url} has no {$label}.",
                    );

                    $position = $this->documentPosition($card, $landmark);

                    $this->assertGreaterThan(
                        $previous,
                        $position,
                        "On {$url} the {$label} is out of order, so the card cannot read top to bottom.",
                    );

                    $previous = $position;
                }
            }

            $this->assertGreaterThan(0, $checked, "No product card was found on {$url}.");
        }
    }

    public function test_the_card_keeps_a_one_to_one_frame_the_quick_view_overlay_and_the_badges(): void
    {
        foreach ($this->cardPageUrls() as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertStringContainsString('aspect-square', $html, "The image frame on {$url} is no longer 1:1.");
            $this->assertStringContainsString('group-hover/img:opacity-100', $html, "The QUICK VIEW overlay is gone from {$url}.");
            $this->assertStringContainsString("\$dispatch('open-quickview'", $html, "QUICK VIEW on {$url} no longer opens the quick view.");
            $this->assertStringContainsString('data-card-price-row', $html);
        }
    }

    /**
     * The add to cart control has to stay a real 44px target on a phone, and it
     * has to stay full width: the owner asked for that explicitly.
     */
    public function test_the_add_to_cart_control_stays_full_width_and_tappable(): void
    {
        foreach ($this->cardPageUrls() as $url) {
            $xpath = $this->xpathFor($url);
            $buttons = $xpath->query('//button[contains(@class, "polygon-btn")][.//span[contains(., "ADD TO CART")]]');

            $this->assertGreaterThan(0, $buttons->length, "No add to cart button on {$url}.");

            foreach ($buttons as $button) {
                /** @var DOMElement $button */
                $class = $button->getAttribute('class');

                $this->assertStringContainsString('w-full', $class, "The add to cart button on {$url} is no longer full width.");
                $this->assertStringContainsString('min-h-11', $class, "The add to cart button on {$url} dropped below the 44px touch target.");
                $this->assertNotSame('', trim($button->getAttribute('aria-label')), 'The add to cart button needs an accessible label.');
            }
        }
    }

    /**
     * Regression guard: the reactive fill binding belongs on the <button>, not
     * on the <i data-lucide> inside it. Moving it back throws
     * "ReferenceError: isInWishlist is not defined" and the heart stops filling.
     */
    public function test_the_wishlist_heart_keeps_its_binding_on_the_button(): void
    {
        foreach ($this->cardPageUrls() as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            // The binding has to sit on the <button> that wraps the icon, and it
            // has to target the svg through the child variant.
            $this->assertMatchesRegularExpression(
                '/<button[^>]*:class="isInWishlist\(\s*\d+\s*\)\s*\?\s*\'\[&_svg\]:fill-[^\']*\'\s*:\s*\'\'"[^>]*>\s*<i data-lucide="heart"/',
                $html,
                "The wishlist binding is no longer on the button on {$url}.",
            );

            $this->assertDoesNotMatchRegularExpression(
                '/<i data-lucide="heart"[^>]*:class=/',
                $html,
                "The wishlist binding is back on the <i> element on {$url}: isInWishlist would throw a ReferenceError there.",
            );
        }
    }

    /**
     * The catalogue grid declares its own columns per breakpoint; the
     * two-column phone grid is the one the owner liked and did not want lost.
     */
    #[DataProvider('cardPages')]
    public function test_the_grid_declares_its_mobile_and_desktop_columns(string $url, string $gridClasses): void
    {
        $this->assertStringContainsString(
            $gridClasses,
            $this->get($url)->assertOk()->getContent(),
            "The grid on {$url} no longer declares its mobile and desktop columns.",
        );
    }

    public function test_the_related_products_grid_keeps_its_breakpoints(): void
    {
        $product = Product::query()->firstOrFail();

        $html = $this->get(route('shop.show', $product->slug))->assertOk()->getContent();

        $this->assertStringContainsString('grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6', $html);
        $this->assertStringContainsString('data-card-price-row', $html, 'Related products did not get the new footer.');
    }

    public function test_the_shop_page_no_longer_paints_cards_with_the_retired_navy_palette(): void
    {
        $html = $this->get('/shop')->assertOk()->getContent();

        foreach (['#081944', '#0A1E54', '#061338', '#B39A84', '#917B68', '#F9F0EE', '#CFC7C8'] as $hex) {
            $this->assertStringNotContainsString($hex, $html, "The shop grid still paints itself with {$hex}.");
        }
    }

    /**
     * The result count is a <strong> inside a translated string, so its colour
     * is re-tinted from app.css. Without that rule the count falls back to
     * #8F6E3B, which is 4.20:1 on the page and just under AA.
     */
    public function test_the_shop_result_count_is_re_tinted_to_pass_wcag_aa(): void
    {
        $this->assertStringContainsString('sozie-result-count', $this->get('/shop')->assertOk()->getContent());

        $css = $this->compiledCss();

        $this->assertMatchesRegularExpression(
            '/\.sozie-result-count strong\s*\{[^}]*color:\s*var\(--gold-light\)|\.sozie-result-count strong\s*\{[^}]*color:\s*#C5A059/i',
            $css,
            'The shop result count is still painted with the old bronze.',
        );
    }

    /**
     * Document order index of a node among the card's descendants. XPath's
     * `preceding::` cannot be used for this: the footer is a following sibling
     * of the details block, so no element is both a preceding sibling *and* an
     * ancestor of the notes.
     */
    private function documentPosition(DOMElement $card, DOMNode $node): int
    {
        $xpath = new DOMXPath($node->ownerDocument);
        $descendants = $xpath->query('.//*', $card);

        foreach ($descendants as $index => $descendant) {
            if ($descendant->isSameNode($node)) {
                return $index;
            }
        }

        $this->fail('Expected the node to be a descendant of the card it is being compared in.');
    }

    private function xpathFor(string $url): DOMXPath
    {
        $document = new DOMDocument;

        libxml_use_internal_errors(true);
        $document->loadHTML('<!DOCTYPE html><html><body>'.$this->get($url)->assertOk()->getContent().'</body></html>', LIBXML_NOERROR);
        libxml_clear_errors();

        return new DOMXPath($document);
    }

    /**
     * The `navy-card` class is a legacy *name*; the rule behind it already
     * paints #17130F. This only guards that the class still resolves, so a
     * rename cannot silently strip the card surface.
     */
    public function test_the_navy_card_class_still_paints_the_obsidian_card_surface(): void
    {
        $this->assertStringContainsString('.navy-card', $this->compiledCss());
        $this->assertStringContainsString('#17130F', $this->compiledCss());
    }

    private function compiledCss(): string
    {
        $manifest = public_path('build/manifest.json');

        $this->assertFileExists($manifest, 'public/build is committed; run npm run build.');

        $assets = json_decode((string) file_get_contents($manifest), true);
        $css = collect($assets['resources/css/app.css'] ?? [])->first();

        $this->assertNotNull($css, 'app.css is missing from the Vite manifest.');

        return (string) file_get_contents(public_path('build/'.$css));
    }
}
