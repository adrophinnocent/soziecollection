<?php

namespace App\Support;

/**
 * Repairs and fills out the cart that lives in the session.
 *
 * The cart is session data, so it outlives deploys. An entry written by an older
 * release can be missing keys that the current views and totals read directly,
 * which turns a routine page view into an undefined array key 500. Every read of
 * the cart therefore goes through here, which also writes the repaired cart back
 * so the checkout form and the place order POST see the same shape.
 */
final class CartItems
{
    /**
     * @param  array<array-key, mixed>  $cart
     * @return array<string, array<string, mixed>>
     */
    public static function normalise(array $cart): array
    {
        $normalised = [];

        foreach ($cart as $key => $item) {
            if (! is_array($item)) {
                continue;
            }

            $cartKey = (string) ($item['cart_key'] ?? $key);
            $image = trim((string) ($item['image'] ?? ''));

            $normalised[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => (int) ($item['product_id'] ?? 0),
                'name' => (string) ($item['name'] ?? __('Product')),
                'slug' => (string) ($item['slug'] ?? ''),
                'size' => (string) ($item['size'] ?? ''),
                'price' => max(0.0, (float) ($item['price'] ?? 0)),
                'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                'image' => $image !== '' ? $image : asset('images/product-placeholder.svg'),
                'category' => (string) ($item['category'] ?? ''),
            ];
        }

        return $normalised;
    }

    /**
     * Read the cart from the session, repair it, and persist the repair.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function fromSession(): array
    {
        $normalised = self::normalise((array) session()->get('cart', []));

        if ($normalised !== (array) session()->get('cart', [])) {
            session()->put('cart', $normalised);
        }

        return $normalised;
    }

    /**
     * @param  array<string, array<string, mixed>>  $cart
     */
    public static function subtotal(array $cart): float
    {
        $subtotal = 0.0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return $subtotal;
    }
}
