<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignLinksTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_the_marketing_page_offers_a_campaign_links_tab(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get(route('admin.marketing'));

        $response->assertOk();
        $response->assertSee('Campaign Links');
        $response->assertSee(route('admin.marketing', ['tab' => 'campaign-links']), false);

        // The other two sections are still reachable, just not all on one page.
        $response->assertSee('Campaign Banners');
        $response->assertSee('Promotional Coupons');
    }

    public function test_a_campaign_link_tab_renders_the_absolute_product_link(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $product = Product::query()->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();

        // Absolute, so it can be pasted straight into a WhatsApp status or a
        // caption: the host comes from the request, never a hardcoded domain.
        $expected = route('shop.show', $product->slug);

        $this->assertStringStartsWith(config('app.url'), $expected);
        $response->assertSee($expected, false);
        $response->assertSee('Copy link');
        $response->assertSee('Copy description');
    }

    public function test_the_description_falls_back_when_the_product_has_none(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $product = Product::query()->firstOrFail();

        // `description` is a NOT NULL column, so "empty" is a blank string in
        // practice; a null is the shape a future migration would produce.
        $product->update(['description' => '', 'fragrance_story' => null]);

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();
        $response->assertSee('Discover '.$product->name.', a handcrafted luxury fragrance from the Sozie Collection atelier.');
    }

    public function test_the_description_falls_back_to_the_fragrance_story_first(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $product = Product::query()->firstOrFail();

        $product->update([
            'description' => '',
            'fragrance_story' => 'A slow-blooming oud built for the long evening.',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();
        $response->assertSee('A slow-blooming oud built for the long evening.');
    }

    public function test_the_whatsapp_share_link_carries_the_description_and_the_product_link(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $product = Product::query()->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();

        $expected = 'https://wa.me/'.preg_replace('/\D+/', '', (string) config('payment.whatsapp.phone_number'))
            .'?text='.rawurlencode($product->description."\n\n".route('shop.show', $product->slug));

        $this->assertStringContainsString(route('shop.show', $product->slug), rawurldecode($expected));
        $response->assertSee($expected, false);
        $response->assertSee('rel="noopener"', false);
    }

    public function test_a_product_that_is_out_of_stock_is_flagged(): void
    {
        $admin = User::factory()->superAdmin()->create();
        $product = Product::query()->firstOrFail();
        $product->update(['is_available' => false, 'stock_quantity' => 0]);

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();
        $response->assertSee('Out of stock');
    }

    public function test_the_campaign_links_tab_is_searchable_and_filterable_without_a_library(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();
        // Alpine only: the admin layout ships no other JS bundle.
        $response->assertSee('x-model.debounce.200ms="search"', false);
        $response->assertSee('x-model="availability"', false);
        $response->assertSee('All availability');
    }

    public function test_the_copy_buttons_fall_back_to_exec_command_off_a_secure_origin(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']));

        $response->assertOk();
        // navigator.clipboard is undefined on an insecure origin, so the
        // hidden-textarea path has to be present in the same component.
        $response->assertSee('window.isSecureContext && navigator.clipboard', false);
        $response->assertSee("document.execCommand('copy')", false);
        $response->assertSee('Copied to clipboard');
    }

    /**
     * The admin panel is light, so the storefront's gold (#A8895F) is only
     * 3.28:1 on the white campaign rows. The category label therefore uses a
     * darker bronze, and this is what stops it drifting back.
     */
    public function test_the_campaign_row_label_colour_passes_wcag_aa_on_the_light_panel(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $html = $this->actingAs($admin)->get(route('admin.marketing', ['tab' => 'campaign-links']))->assertOk()->getContent();

        $this->assertSame(1, preg_match('/<p class="[^"]*tracking-wider text-\[(#[0-9A-Fa-f]{6})\]">/', $html, $matches));

        $ratio = $this->contrastRatio($matches[1], '#FFFFFF');

        $this->assertGreaterThanOrEqual(
            4.5,
            $ratio,
            sprintf('The campaign category label %s is only %.2f:1 on the white admin row.', $matches[1], $ratio),
        );
    }

    /**
     * WCAG 2.x relative luminance contrast ratio.
     */
    private function contrastRatio(string $foreground, string $background): float
    {
        $luminance = function (string $hex): float {
            $channels = array_map(
                fn (string $pair) => (int) hexdec($pair) / 255,
                str_split(ltrim($hex, '#'), 2),
            );

            $linear = array_map(
                fn (float $channel) => $channel <= 0.03928 ? $channel / 12.92 : (($channel + 0.055) / 1.055) ** 2.4,
                $channels,
            );

            return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
        };

        $a = $luminance($foreground);
        $b = $luminance($background);

        return (max($a, $b) + 0.05) / (min($a, $b) + 0.05);
    }

    public function test_a_non_privileged_user_cannot_reach_the_campaign_links(): void
    {
        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.marketing', ['tab' => 'campaign-links']))->assertForbidden();
    }

    public function test_a_guest_cannot_reach_the_campaign_links(): void
    {
        $this->get(route('admin.marketing', ['tab' => 'campaign-links']))->assertRedirect(route('login'));
    }
}
