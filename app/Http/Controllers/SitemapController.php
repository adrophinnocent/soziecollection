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
        $xml = $this->generateSitemapXml();

        // Also save physical public/sitemap.xml file for direct web server serving
        try {
            @file_put_contents(public_path('sitemap.xml'), $xml);
        } catch (\Throwable $e) {
            // Ignore if write-protected
        }

        return response($xml, 200)
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function generateSitemapXml(): string
    {
        $baseUrl = Setting::get('canonical_url');
        if (empty($baseUrl) || ! is_string($baseUrl) || ! str_starts_with($baseUrl, 'http')) {
            $baseUrl = config('app.url');
            if (empty($baseUrl) || ! is_string($baseUrl) || ! str_starts_with($baseUrl, 'http') || str_contains($baseUrl, 'localhost')) {
                $baseUrl = 'https://soziecollection.twinasafaris.com';
            }
        }
        $baseUrl = rtrim($baseUrl, '/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        // Homepage
        $xml .= "    <url>\n";
        $xml .= '        <loc>'.htmlspecialchars("{$baseUrl}/", ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
        $xml .= '        <lastmod>'.date('Y-m-d')."</lastmod>\n";
        $xml .= "        <changefreq>daily</changefreq>\n";
        $xml .= "        <priority>1.0</priority>\n";
        $xml .= "    </url>\n";

        // Shop
        $xml .= "    <url>\n";
        $xml .= '        <loc>'.htmlspecialchars("{$baseUrl}/shop", ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
        $xml .= '        <lastmod>'.date('Y-m-d')."</lastmod>\n";
        $xml .= "        <changefreq>daily</changefreq>\n";
        $xml .= "        <priority>0.9</priority>\n";
        $xml .= "    </url>\n";

        // Track Order
        $xml .= "    <url>\n";
        $xml .= '        <loc>'.htmlspecialchars("{$baseUrl}/track-order", ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
        $xml .= '        <lastmod>'.date('Y-m-d')."</lastmod>\n";
        $xml .= "        <changefreq>monthly</changefreq>\n";
        $xml .= "        <priority>0.5</priority>\n";
        $xml .= "    </url>\n";

        // Categories
        try {
            $categories = Category::all();
            foreach ($categories as $category) {
                if (empty($category->slug)) {
                    continue;
                }
                $lastmod = $category->updated_at ? $category->updated_at->format('Y-m-d') : date('Y-m-d');
                $url = "{$baseUrl}/shop?category=".urlencode($category->slug);
                $xml .= "    <url>\n";
                $xml .= '        <loc>'.htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
                $xml .= "        <lastmod>{$lastmod}</lastmod>\n";
                $xml .= "        <changefreq>weekly</changefreq>\n";
                $xml .= "        <priority>0.8</priority>\n";
                $xml .= "    </url>\n";
            }
        } catch (\Throwable $e) {
            // Safe fallback if database query fails
        }

        // Products
        try {
            $products = Product::where('is_available', true)->get();
            foreach ($products as $product) {
                if (empty($product->slug)) {
                    continue;
                }
                $lastmod = $product->updated_at ? $product->updated_at->format('Y-m-d') : date('Y-m-d');
                $url = "{$baseUrl}/product/".urlencode($product->slug);
                $xml .= "    <url>\n";
                $xml .= '        <loc>'.htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8')."</loc>\n";
                $xml .= "        <lastmod>{$lastmod}</lastmod>\n";
                $xml .= "        <changefreq>weekly</changefreq>\n";
                $xml .= "        <priority>0.9</priority>\n";
                $xml .= "    </url>\n";
            }
        } catch (\Throwable $e) {
            // Safe fallback if database query fails
        }

        $xml .= '</urlset>';

        return $xml;
    }

    public function robots(): Response
    {
        $baseUrl = Setting::get('canonical_url');
        if (empty($baseUrl) || ! is_string($baseUrl) || ! str_starts_with($baseUrl, 'http')) {
            $baseUrl = config('app.url');
            if (empty($baseUrl) || ! is_string($baseUrl) || ! str_starts_with($baseUrl, 'http') || str_contains($baseUrl, 'localhost')) {
                $baseUrl = 'https://soziecollection.twinasafaris.com';
            }
        }
        $baseUrl = rtrim($baseUrl, '/');

        $robotsSetting = Setting::get('robots_setting', 'index, follow');

        if (str_contains($robotsSetting, 'noindex')) {
            $content = "User-agent: *\nDisallow: /\n\nSitemap: {$baseUrl}/sitemap.xml\n";
        } else {
            $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /account\nDisallow: /checkout\n\nSitemap: {$baseUrl}/sitemap.xml\n";
        }

        // Also save physical public/robots.txt file for direct web server serving
        try {
            @file_put_contents(public_path('robots.txt'), $content);
        } catch (\Throwable $e) {
            // Ignore if write-protected
        }

        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
