<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'variants')->where('is_available', true);

        // Filter by search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('top_notes', 'like', "%{$search}%")
                    ->orWhere('heart_notes', 'like', "%{$search}%")
                    ->orWhere('base_notes', 'like', "%{$search}%")
                    ->orWhere('scent_type', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by scent type / mood
        if ($request->filled('scent_type')) {
            $query->where('scent_type', $request->scent_type);
        }

        // Filter by collections (best seller, new arrival, limited edition)
        if ($request->filled('collection')) {
            if ($request->collection === 'best_seller') {
                $query->where('is_best_seller', true);
            } elseif ($request->collection === 'new_arrival') {
                $query->where('is_new_arrival', true);
            } elseif ($request->collection === 'limited_edition') {
                $query->where('is_limited_edition', true);
            }
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort', 'featured')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('is_best_seller', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $scentTypes = ['Fresh', 'Sweet', 'Floral', 'Fruity', 'Woody', 'Spicy', 'Oriental', 'Musky', 'Vanilla', 'Citrus'];

        return view('shop.index', compact('products', 'categories', 'scentTypes'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'variants', 'reviews'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Related Products from same category or gender
        $relatedProducts = Product::with('category', 'variants')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $extraProducts = Product::with('category', 'variants')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->where('is_available', true)
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($extraProducts);
        }

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $id,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified' => true,
        ]);

        return back()->with('success', 'Asante! Maoni yako yamewasilishwa kikamilifu.');
    }

    public function quickView($id)
    {
        $product = Product::with(['category', 'variants'])->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category ? $product->category->name : 'Signature',
            'price' => (float) $product->effective_price,
            'formatted_price' => $product->formatted_price,
            'original_price' => (float) $product->price,
            'discount_percentage' => $product->discount_percentage,
            'description' => $product->description,
            'top_notes' => $product->top_notes,
            'heart_notes' => $product->heart_notes,
            'base_notes' => $product->base_notes,
            'scent_type' => $product->scent_type,
            'gender' => $product->gender,
            'concentration' => $product->concentration,
            'image' => $product->primary_image,
            'video_url' => $product->video_url,
            'embed_video_url' => $product->embed_video_url,
            'variants' => $product->variants->map(function ($v) {
                return [
                    'size' => $v->size,
                    'price' => (float) $v->price,
                    'formatted_price' => 'TZS '.number_format($v->price, 0, '.', ','),
                ];
            }),
        ]);
    }

    public function fragranceFinder(Request $request)
    {
        $scent = $request->get('scent_type');
        $occasion = $request->get('occasion');

        $query = Product::with('category', 'variants')->where('is_available', true);

        if ($scent) {
            $query->where(function ($q) use ($scent) {
                $q->where('scent_type', 'like', "%{$scent}%")
                    ->orWhere('fragrance_family', 'like', "%{$scent}%");
            });
        }

        if ($occasion) {
            $query->where('occasion', 'like', "%{$occasion}%");
        }

        $matches = $query->take(3)->get();

        if ($matches->isEmpty()) {
            $matches = Product::with('category', 'variants')->where('is_featured', true)->take(3)->get();
        }

        return response()->json([
            'status' => 'success',
            'matches' => $matches,
        ]);
    }
}
