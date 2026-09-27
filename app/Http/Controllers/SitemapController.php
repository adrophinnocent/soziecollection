<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(Setting::get('canonical_url', 'https://soziecollection.twinasafaris.com'), '/');

        $products = Product::where('is_available', true)->get();
        $categories = Category::all();

        $content = view('sitemap', compact('baseUrl', 'products', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $baseUrl = rtrim(Setting::get('canonical_url', 'https://soziecollection.twinasafaris.com'), '/');
        $robotsSetting = Setting::get('robots_setting', 'index, follow');

        if (str_contains($robotsSetting, 'noindex')) {
            $content = "User-agent: *\nDisallow: /\n\nSitemap: {$baseUrl}/sitemap.xml\n";
        } else {
            $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /account\nDisallow: /checkout\n\nSitemap: {$baseUrl}/sitemap.xml\n";
        }

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
