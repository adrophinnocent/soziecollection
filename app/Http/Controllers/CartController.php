<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $size = $request->input('size') ?: ($product->default_size ?: '50ml Signature Bottle');
        $quantity = (int) $request->input('quantity', 1);

        // Find variant price if available
        $variant = ProductVariant::where('product_id', $product->id)
            ->where('size', $size)
            ->first();

        $price = $variant ? $variant->price : $product->effective_price;

        $cart = session()->get('cart', []);
        $cartKey = $product->id.'_'.Str::slug($size);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'cart_key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => $size,
                'price' => (float) $price,
                'quantity' => $quantity,
                'image' => $product->primary_image,
                'category' => $product->category ? $product->category->name : 'Fragrance',
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Product added to cart!'),
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'cart' => array_values($cart),
                'total' => array_reduce($cart, function ($acc, $item) {
                    return $acc + ($item['price'] * $item['quantity']);
                }, 0),
            ]);
        }

        return back()->with('success', __(':product added to cart!', ['product' => $product->name]));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->cart_key;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->quantity;
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'cart' => array_values($cart),
                'total' => array_reduce($cart, function ($acc, $item) {
                    return $acc + ($item['price'] * $item['quantity']);
                }, 0),
            ]);
        }

        return back()->with('success', __('Cart updated'));
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->cart_key;

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'cart' => array_values($cart),
                'total' => array_reduce($cart, function ($acc, $item) {
                    return $acc + ($item['price'] * $item['quantity']);
                }, 0),
            ]);
        }

        return back()->with('success', __('Item removed from cart'));
    }

    public function getCartApi()
    {
        $cart = session()->get('cart', []);
        $total = array_reduce($cart, function ($acc, $item) {
            return $acc + ($item['price'] * $item['quantity']);
        }, 0);

        return response()->json([
            'cart' => array_values($cart),
            'cart_count' => array_sum(array_column($cart, 'quantity')),
            'total' => $total,
            'formatted_total' => 'TZS '.number_format($total, 0, '.', ','),
        ]);
    }
}
