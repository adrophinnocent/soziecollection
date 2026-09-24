<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SozieCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SOZIE COLLECTION');
    }

    public function test_language_switch_between_english_and_kiswahili()
    {
        // Switch to Swahili
        $switchResponse = $this->get('/lang/sw');
        $switchResponse->assertRedirect();
        $this->assertEquals('sw', session('locale'));

        // Visit home page in Swahili
        $homeSw = $this->get('/');
        $homeSw->assertStatus(200);
        $homeSw->assertSee('Nyumbani');
        $homeSw->assertSee('Duka la Marashi');

        // Switch to English
        $switchResponseEn = $this->get('/lang/en');
        $switchResponseEn->assertRedirect();
        $this->assertEquals('en', session('locale'));

        $homeEn = $this->get('/');
        $homeEn->assertStatus(200);
        $homeEn->assertSee('Home');
        $homeEn->assertSee('Shop Collection');
    }

    public function test_shop_page_loads_and_lists_products()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('SOZIE ELEGANCE');
    }

    public function test_product_detail_page_loads()
    {
        $product = Product::first();
        $response = $this->get('/product/'.$product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('ABOUT SOZIE ELEGANCE');
    }

    public function test_fragrance_finder_api_returns_matches()
    {
        $response = $this->getJson('/api/fragrance-finder?scent_type=Floral&occasion=Date+Night');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'matches',
        ]);
    }

    public function test_cart_add_and_get_api()
    {
        $product = Product::first();

        $addResponse = $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'size' => '50ml Signature Bottle',
            'quantity' => 2,
        ]);

        $addResponse->assertStatus(200);
        $addResponse->assertJsonPath('status', 'success');

        $cartApiResponse = $this->getJson('/api/cart');
        $cartApiResponse->assertStatus(200);
        $cartApiResponse->assertJsonPath('cart_count', 2);
    }

    public function test_checkout_and_order_creation()
    {
        $product = Product::first();

        // Add to cart
        $this->postJson('/cart/add', [
            'product_id' => $product->id,
            'size' => '50ml Signature Bottle',
            'quantity' => 1,
        ]);

        $checkoutResponse = $this->post('/checkout', [
            'customer_name' => 'Fatma Juma',
            'customer_phone' => '0789123456',
            'customer_email' => 'fatma@example.com',
            'city' => 'Dar es Salaam',
            'shipping_address' => 'Oysterbay, Toure Drive, Plot 12',
            'payment_method' => 'whatsapp',
            'notes' => 'Please deliver in afternoon',
            'checkout_mode' => 'guest',
        ]);

        $checkoutResponse->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Fatma Juma',
            'customer_phone' => '0789123456',
            'whatsapp_ordered' => true,
        ]);
    }

    public function test_admin_dashboard_loads()
    {
        $admin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Analytics & Store Overview');
    }

    public function test_admin_modules_load()
    {
        $admin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

        $this->actingAs($admin)->get('/admin/customers')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/marketing')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/reports')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/content')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/users')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/payments')->assertStatus(200);
    }
}
