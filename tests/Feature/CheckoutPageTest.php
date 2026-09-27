<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function makeAddress(User $user, string $label, bool $isDefault): Address
    {
        /** @var Address $address */
        $address = Address::query()->create([
            'user_id' => $user->id,
            'label' => $label,
            'full_name' => $user->name,
            'phone' => '0712345678',
            'city' => 'Dar es Salaam',
            'street_address' => 'Masaki, Haile Selassie Road',
            'is_default' => $isDefault,
        ]);

        return $address;
    }

    /**
     * A cart item exactly as CartController@add() writes it into the session.
     *
     * @return array<string, array<string, mixed>>
     */
    private function cartSession(): array
    {
        $product = Product::query()->firstOrFail();
        $price = (float) ($product->variants()->min('price') ?? $product->base_price ?? 45000);

        return [
            $product->id.'_50ml' => [
                'cart_key' => $product->id.'_50ml',
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => '50ml',
                'price' => $price,
                'quantity' => 1,
                'image' => $product->primary_image,
            ],
        ];
    }

    public function test_a_guest_with_a_cart_reaches_the_checkout_form(): void
    {
        $this->withSession(['cart' => $this->cartSession()])
            ->get('/checkout')
            ->assertOk();
    }

    public function test_a_signed_in_customer_with_saved_addresses_reaches_the_checkout_form(): void
    {
        $user = User::factory()->create();

        $this->makeAddress($user, 'Home', true);
        $this->makeAddress($user, 'Office', false);

        $this->actingAs($user)
            ->withSession(['cart' => $this->cartSession()])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Home')
            ->assertSee('Office');
    }

    public function test_an_admin_with_a_cart_reaches_the_checkout_form(): void
    {
        $admin = User::factory()->superAdmin()->create();

        $this->actingAs($admin)
            ->withSession(['cart' => $this->cartSession()])
            ->get('/checkout')
            ->assertOk();
    }

    public function test_an_empty_cart_redirects_to_the_shop_instead_of_erroring(): void
    {
        $this->get('/checkout')
            ->assertRedirect(route('shop.index'))
            ->assertSessionHas('info');
    }

    public function test_a_cart_holding_one_item_still_renders_every_summary_key(): void
    {
        $response = $this->withSession(['cart' => $this->cartSession()])->get('/checkout');

        $response->assertOk();

        foreach (['image', 'name', 'price', 'quantity', 'size'] as $key) {
            $response->assertDontSee("\$item['".$key."']");
        }
    }

    public function test_a_stale_cart_entry_missing_a_key_does_not_break_the_page(): void
    {
        $cart = $this->cartSession();
        $key = array_key_first($cart);
        $stale = $cart[$key];
        unset($stale['image'], $stale['size']);
        $cart[$key] = $stale;

        $this->withSession(['cart' => $cart])->get('/checkout')->assertOk();
    }
}
