<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\WhatsappOrderMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsappOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_checkout_saves_the_product_image_with_the_order_item(): void
    {
        $product = Product::first();

        $response = $this->withSession([
            'cart' => [
                $product->id.'_50ml' => [
                    'cart_key' => $product->id.'_50ml',
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'size' => '50ml',
                    'price' => 50000,
                    'quantity' => 1,
                    'image' => $product->primary_image,
                ],
            ],
        ])->post('/checkout', [
            'customer_name' => 'Fatma Juma',
            'customer_phone' => '0789123456',
            'customer_email' => 'fatma@example.com',
            'city' => 'Dar es Salaam',
            'shipping_address' => 'Oysterbay, Toure Drive',
            'payment_method' => 'whatsapp',
            'checkout_mode' => 'guest',
        ]);

        $response->assertRedirect();

        $item = OrderItem::firstOrFail();

        $this->assertSame($product->id, $item->product_id);
        $this->assertSame($product->primary_image, $item->product_image);

        // The customer opens this link to look at the bottle they bought, so the
        // resolved URL has to point at a file this repository actually serves.
        // Seeded rows carry the bundled placeholder, which lives under public/
        // rather than on the public disk.
        $path = public_path(ltrim((string) parse_url((string) $item->product_image_url, PHP_URL_PATH), '/'));

        $this->assertFileExists($path);
        $this->assertSame(
            realpath(public_path('images/product-placeholder.svg')),
            realpath($path),
            'The order share link must resolve to the committed brand placeholder, not a third-party photo.'
        );
    }

    public function test_whatsapp_message_contains_product_image_and_signed_design_link(): void
    {
        $order = $this->createOrder();
        $message = app(WhatsappOrderMessage::class)->build($order);
        $shareUrl = app(WhatsappOrderMessage::class)->shareUrl($order);

        $this->assertStringContainsString($order->order_number, $message);
        $this->assertStringContainsString($order->items->first()->product_name, $message);
        $this->assertStringContainsString('Picha: '.$order->items->first()->product_image_url, $message);
        $this->assertStringContainsString('Angalia design ya oda', $message);
        $this->assertStringContainsString($shareUrl, $message);
    }

    public function test_signed_order_share_page_displays_the_image_without_customer_contact_details(): void
    {
        $order = $this->createOrder();
        $shareUrl = app(WhatsappOrderMessage::class)->shareUrl($order);

        $response = $this->get($shareUrl);

        $response->assertOk();
        $response->assertSee('Your Signature Order');
        $response->assertSee($order->items->first()->product_name);
        $response->assertSee('images/product-placeholder.svg', false);
        $response->assertDontSee($order->customer_phone);
        $response->assertDontSee($order->shipping_address);
    }

    public function test_unsigned_order_share_page_is_forbidden(): void
    {
        $order = $this->createOrder();

        $this->get(route('orders.share', $order))->assertForbidden();
    }

    private function createOrder(): Order
    {
        $product = Product::first();

        $order = Order::create([
            'order_number' => 'SOZ-TEST-001',
            'customer_name' => 'Fatma Juma',
            'customer_phone' => '0789123456',
            'city' => 'Dar es Salaam',
            'shipping_address' => 'Oysterbay, Toure Drive',
            'subtotal' => 50000,
            'shipping_cost' => 5000,
            'total_amount' => 55000,
            'status' => 'new',
            'payment_method' => 'whatsapp',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_image' => $product->primary_image,
            'product_name' => $product->name,
            'variant_size' => '50ml',
            'quantity' => 1,
            'unit_price' => 50000,
            'subtotal' => 50000,
        ]);

        return $order->fresh('items.product');
    }
}
