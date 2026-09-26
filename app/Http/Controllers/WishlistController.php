<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function indexApi(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'logged_in' => false,
                'items' => [],
                'count' => 0,
            ]);
        }

        $user = Auth::user();
        $items = Wishlist::with('product:id,name,slug')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(fn (Wishlist $w) => [
                'id' => $w->product_id,
                'product_id' => $w->product_id,
                'name' => $w->product?->name ?? __('Product'),
                'slug' => $w->product?->slug,
                'image' => $w->product?->primary_image,
                'formatted_price' => $w->product?->formatted_price,
                'price' => $w->product?->effective_price,
            ]);

        return response()->json([
            'logged_in' => true,
            'items' => $items,
            'count' => $items->count(),
        ]);
    }

    public function toggle(Request $request): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json([
                'logged_in' => false,
                'status' => 'error',
                'message' => __('Please login to manage wishlist.'),
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $user = Auth::user();
        $existing = Wishlist::where([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
        ])->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
            ]);
            $added = true;
        }

        $product = Product::find($validated['product_id']);

        return response()->json([
            'logged_in' => true,
            'status' => 'success',
            'added' => $added,
            'message' => $added
                ? __(':product added to wishlist.', ['product' => $product->name])
                : __(':product removed from wishlist.', ['product' => $product->name]),
            'count' => Wishlist::where('user_id', $user->id)->count(),
            'item' => [
                'id' => $product->id,
                'name' => $product->name,
                'formatted_price' => $product->formatted_price,
                'image' => $product->primary_image,
            ],
        ]);
    }

    public function remove(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('info', __('Please login to manage your wishlist.'));
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $user = Auth::user();
        Wishlist::where([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
        ])->delete();

        return back()->with('success', __('Item removed from your wishlist.'));
    }

    public function moveToCart(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('info', __('Please login to move wishlist items to cart.'));
        }

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'size' => ['nullable', 'string', 'max:50'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = Auth::user();
        $item = Wishlist::where([
            'user_id' => $user->id,
            'product_id' => $validated['product_id'],
        ])->first();

        if (! $item) {
            return back()->with('error', __('Item not found in wishlist.'));
        }

        $cartRequest = new Request([
            'product_id' => $validated['product_id'],
            'size' => $validated['size'] ?? null,
            'quantity' => $validated['quantity'] ?? 1,
        ]);
        $cartController = app(CartController::class);
        $cartController->add($cartRequest);

        $item->delete();

        return back()->with('success', __('Moved to cart successfully.'));
    }
}
