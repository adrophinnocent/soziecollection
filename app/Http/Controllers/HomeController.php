<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $allBanners = Banner::query()
            ->active()
            ->ordered()
            ->get();

        $allProducts = Product::with('variants')->where('is_available', true)->get();

        $resolveSmartLink = function (Banner $banner) use ($allProducts): string {
            if ($banner->button_link && $banner->button_link !== '/shop' && $banner->button_link !== route('shop.index')) {
                return $banner->button_link;
            }

            $searchTerms = array_filter([$banner->title, $banner->headline, $banner->eyebrow]);
            foreach ($searchTerms as $term) {
                $trimmed = trim($term);
                if (mb_strlen($trimmed) < 3) {
                    continue;
                }

                $matched = $allProducts->first(function (Product $p) use ($trimmed) {
                    return Str::contains(strtolower($p->name), strtolower($trimmed))
                        || Str::contains(strtolower($trimmed), strtolower($p->name));
                });

                if ($matched) {
                    return route('shop.show', $matched->slug);
                }
            }

            return $banner->button_link ?: route('shop.index');
        };

        $heroSlides = $allBanners
            ->filter(fn (Banner $b) => $b->show_in_hero)
            ->map(fn (Banner $banner): array => [
                'image' => $banner->image_url,
                'mobile_image' => $banner->mobile_image_url,
                'title' => $banner->title,
                'eyebrow' => $banner->eyebrow ?: '',
                'headline' => $banner->headline ?: $banner->title,
                'highlight_text' => $banner->highlight_text ?: '',
                'description' => $banner->subtitle ?: '',
                'button_text' => $banner->button_text ?: __('Shop Collection'),
                'button_link' => $resolveSmartLink($banner),
                'secondary_button_text' => $banner->secondary_button_text ?: '',
                'secondary_button_link' => $banner->secondary_button_link ?: '',
            ])
            ->values();

        $gallerySlides = $allBanners
            ->filter(fn (Banner $b) => $b->show_in_gallery)
            ->map(fn (Banner $banner): array => [
                'image' => $banner->image_url,
                'mobile_image' => $banner->mobile_image_url,
                'title' => $banner->title,
                'eyebrow' => $banner->eyebrow ?: '',
                'headline' => $banner->headline ?: $banner->title,
                'highlight_text' => $banner->highlight_text ?: '',
                'subtitle' => $banner->subtitle ?: '',
                'button_link' => $resolveSmartLink($banner),
                'tag' => '@soziecollection',
                'handle' => '@soziecollection',
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

        return view('home', compact(
            'heroSlides',
            'gallerySlides',
            'categories',
            'bestSellers',
            'newArrivals',
            'featuredProducts',
            'reviews',
            'allProducts'
        ));
    }
}
