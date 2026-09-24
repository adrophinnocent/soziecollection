<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        $heroBanner = Banner::where('is_active', true)->orderBy('sort_order')->first();

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
            'heroBanner',
            'categories',
            'bestSellers',
            'newArrivals',
            'featuredProducts',
            'reviews',
            'allProducts'
        ));
    }
}
