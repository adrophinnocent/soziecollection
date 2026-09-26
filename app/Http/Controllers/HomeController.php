<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroSlides = Banner::query()
            ->active()
            ->ordered()
            ->get()
            ->map(fn (Banner $banner): array => [
                'image' => $banner->image_url,
                'mobile_image' => $banner->mobile_image_url,
                'eyebrow' => $banner->eyebrow ?: $banner->title,
                'headline' => $banner->headline ?: $banner->title,
                'highlight_text' => $banner->highlight_text,
                'description' => $banner->subtitle ?: __('Hero Description'),
                'button_text' => $banner->button_text ?: __('Shop Collection'),
                'button_link' => $banner->button_link ?: route('shop.index'),
                'secondary_button_text' => $banner->secondary_button_text,
                'secondary_button_link' => $banner->secondary_button_link,
            ])
            ->values();

        $categories = Category::withCount('products')->get();

        $bestSellers = Product::with('category', 'variants')
            ->where('is_best_seller', true)
            ->where('is_available', true)
            ->take(8)
            ->get();

        $newArrivals = Product::with('category', 'variants')
            ->where('is_new_arrival', true)
            ->where('is_available', true)
            ->take(8)
            ->get();

        $featuredProducts = Product::with('category', 'variants')
            ->where('is_featured', true)
            ->where('is_available', true)
            ->take(4)
            ->get();

        $reviews = Review::with('product')
            ->where('rating', '>=', 4)
            ->take(6)
            ->get();

        $allProducts = Product::with('variants')->where('is_available', true)->get();

        return view('home', compact(
            'heroSlides',
            'categories',
            'bestSellers',
            'newArrivals',
            'featuredProducts',
            'reviews',
            'allProducts'
        ));
    }
}
