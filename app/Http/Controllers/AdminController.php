<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalProducts = Product::count();
        $newOrdersCount = Order::where('status', 'new')->count();

        $recentOrders = Order::with('items')->latest()->take(5)->get();
        $bestSellingProducts = Product::where('is_best_seller', true)->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'newOrdersCount',
            'recentOrders',
            'bestSellingProducts'
        ));
    }

    // Product Management
    public function products()
    {
        $products = Product::with('category')->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'product_type' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:women,men,unisex',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'availability_status' => 'nullable|string',
            'description' => 'required|string',
            'fragrance_story' => 'nullable|string',
            'fragrance_family' => 'required|string',
            'scent_type' => 'required|string',
            'top_notes' => 'required|string',
            'heart_notes' => 'required|string',
            'base_notes' => 'required|string',
            'concentration' => 'nullable|string',
            'default_size' => 'nullable|string',
            'longevity' => 'nullable|string',
            'sillage' => 'nullable|string',
            'intensity' => 'nullable|string',
            'video_url' => 'nullable|string|max:500',
            'image_url' => 'nullable|url',
            'image_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,avif|max:20480',
            'ad_headline' => 'nullable|string',
            'ad_copy' => 'nullable|string',
            'ad_cta' => 'nullable|string',
            'instagram_caption' => 'nullable|string',
            'facebook_caption' => 'nullable|string',
            'tiktok_caption' => 'nullable|string',
            'whatsapp_caption' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'focus_keyword' => 'nullable|string',
            'image_alt' => 'nullable|string',
            'slug' => 'nullable|string',
            'is_best_seller' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_limited_edition' => 'nullable|boolean',
        ]);

        $images = [];

        // Handle uploaded image files
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('products', 'public');
                $images[] = asset('storage/'.$path);
            }
        }

        // Fallback to image URL if no file uploaded
        if (empty($images) && $request->filled('image_url')) {
            $images[] = $request->image_url;
        }

        if (empty($images)) {
            $images[] = asset('images/product-placeholder.svg');
        }

        $validated['slug'] = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name).'-'.Str::random(4);
        $validated['sku'] = $request->filled('sku') ? strtoupper($request->sku) : 'SZ-'.strtoupper(Str::random(5));
        $validated['brand'] = $request->input('brand', 'Sozie Collection');
        $validated['product_type'] = $request->input('product_type', 'Eau de Parfum');
        $validated['concentration'] = $request->input('concentration', 'Eau de Parfum');
        $validated['default_size'] = $request->input('default_size', '50ml');
        $validated['stock_quantity'] = $request->input('stock_quantity', 50);
        $validated['low_stock_threshold'] = $request->input('low_stock_threshold', 5);
        $validated['availability_status'] = $request->input('availability_status', 'in_stock');
        $validated['images'] = $images;
        $validated['campaign_image'] = $images[0];
        $validated['is_best_seller'] = $request->has('is_best_seller');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_limited_edition'] = $request->has('is_limited_edition');

        if ($request->filled('price') && $request->filled('discount_price') && $request->discount_price > 0 && $request->price > $request->discount_price) {
            $validated['discount_percentage'] = round((($request->price - $request->discount_price) / $request->price) * 100);
        }

        $validated['why_you_will_love_it'] = [
            'Premium long-lasting formulation',
            'Crafted for memorable occasions',
            'Housed in luxurious geometric casing',
        ];

        $product = Product::create($validated);

        // Process custom size variants from form inputs
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $v) {
                $hasPrice = isset($v['price']) && is_numeric($v['price']) && $v['price'] > 0;
                $hasSize = ! empty($v['size']);
                $isEnabled = ! empty($v['enabled']);

                if (($isEnabled || $hasPrice) && $hasSize && $hasPrice) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => trim($v['size']),
                        'price' => $v['price'],
                        'discount_price' => ! empty($v['discount_price']) ? $v['discount_price'] : null,
                        'sku' => ! empty($v['sku']) ? strtoupper($v['sku']) : null,
                        'stock_quantity' => isset($v['stock_quantity']) && is_numeric($v['stock_quantity']) ? (int) $v['stock_quantity'] : $validated['stock_quantity'],
                        'is_available' => isset($v['is_available']) ? (bool) $v['is_available'] : true,
                    ]);
                }
            }
        }

        // Fallback: If no custom variants were filled, create variant based on product price and default_size
        if ($product->variants()->count() === 0) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $product->default_size ?: '50ml',
                'price' => $product->effective_price,
                'stock_quantity' => $validated['stock_quantity'],
                'is_available' => true,
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    public function editProduct(int $id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'product_type' => 'nullable|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:women,men,unisex',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'availability_status' => 'nullable|string',
            'description' => 'required|string',
            'fragrance_story' => 'nullable|string',
            'fragrance_family' => 'required|string',
            'scent_type' => 'required|string',
            'top_notes' => 'required|string',
            'heart_notes' => 'required|string',
            'base_notes' => 'required|string',
            'video_url' => 'nullable|string|max:500',
            'image_url' => 'nullable|url',
            'image_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,bmp,avif|max:20480',
            'ad_headline' => 'nullable|string',
            'ad_copy' => 'nullable|string',
            'ad_cta' => 'nullable|string',
            'instagram_caption' => 'nullable|string',
            'facebook_caption' => 'nullable|string',
            'tiktok_caption' => 'nullable|string',
            'whatsapp_caption' => 'nullable|string',
            'seo_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'focus_keyword' => 'nullable|string',
            'image_alt' => 'nullable|string',
            'slug' => 'nullable|string',
            'is_best_seller' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_limited_edition' => 'nullable|boolean',
        ]);

        $images = $product->images ?: [];

        if ($request->hasFile('image_files')) {
            $newImages = [];
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('products', 'public');
                $newImages[] = asset('storage/'.$path);
            }
            if (! empty($newImages)) {
                $images = array_merge($newImages, $images);
            }
        } elseif ($request->filled('image_url')) {
            array_unshift($images, $request->image_url);
        }

        $validated['images'] = $images;
        if (! empty($images)) {
            $validated['campaign_image'] = $images[0];
        }

        if ($request->filled('slug')) {
            $validated['slug'] = Str::slug($request->slug);
        }

        $validated['is_best_seller'] = $request->has('is_best_seller');
        $validated['is_new_arrival'] = $request->has('is_new_arrival');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_limited_edition'] = $request->has('is_limited_edition');

        if ($request->filled('price') && $request->filled('discount_price') && $request->discount_price > 0 && $request->price > $request->discount_price) {
            $validated['discount_percentage'] = round((($request->price - $request->discount_price) / $request->price) * 100);
        }

        $product->update($validated);

        // Update variants: delete old ones and recreate checked/filled ones
        if ($request->has('variants') && is_array($request->variants)) {
            $product->variants()->delete();

            foreach ($request->variants as $v) {
                $hasPrice = isset($v['price']) && is_numeric($v['price']) && $v['price'] > 0;
                $hasSize = ! empty($v['size']);
                $isEnabled = ! empty($v['enabled']);

                if (($isEnabled || $hasPrice) && $hasSize && $hasPrice) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => trim($v['size']),
                        'price' => $v['price'],
                        'discount_price' => ! empty($v['discount_price']) ? $v['discount_price'] : null,
                        'sku' => ! empty($v['sku']) ? strtoupper($v['sku']) : null,
                        'stock_quantity' => isset($v['stock_quantity']) && is_numeric($v['stock_quantity']) ? (int) $v['stock_quantity'] : $product->stock_quantity,
                        'is_available' => isset($v['is_available']) ? (bool) $v['is_available'] : true,
                    ]);
                }
            }
        }

        if ($product->variants()->count() === 0) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $product->default_size ?: '50ml',
                'price' => $product->effective_price,
                'stock_quantity' => $product->stock_quantity,
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', 'Product deleted');
    }

    // Orders Management
    public function orders()
    {
        $orders = Order::with('items')->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['status' => 'required|in:new,confirmed,processing,shipped,delivered,cancelled']);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to '.ucfirst($request->status));
    }

    public function customers()
    {
        $customers = User::where('role', User::ROLE_CUSTOMER)
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

    public function marketing()
    {
        $banners = Banner::ordered()->get();
        $coupons = Coupon::latest()->get();

        return view('admin.marketing.index', compact('banners', 'coupons'));
    }

    public function reports()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $placedOrders = Order::where('status', 'new')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $topSelling = Product::where('is_best_seller', true)->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'placedOrders',
            'deliveredOrders',
            'cancelledOrders',
            'topSelling'
        ));
    }

    public function content()
    {
        $banners = Banner::ordered()->get();

        return view('admin.content.index', compact('banners'));
    }

    public function adminUsers()
    {
        $adminUsers = User::where('role', '!=', User::ROLE_CUSTOMER)
            ->latest()
            ->get();

        return view('admin.users.index', compact('adminUsers'));
    }

    public function payments()
    {
        $config = config('payment');

        return view('admin.payments.index', compact('config'));
    }

    public function reviews()
    {
        $reviews = Review::with('product')->latest()->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateReviewStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,approved,hidden',
            'is_featured' => 'nullable|boolean',
        ]);

        if ($request->has('status')) {
            $review->status = $request->status;
        }

        if ($request->has('is_featured')) {
            $review->is_featured = $request->boolean('is_featured');
        }

        $review->save();

        return back()->with('success', 'Review status updated successfully.');
    }

    public function deleteReview(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }

    public function businessSettings()
    {
        $settings = Setting::getAllGroup('business');

        return view('admin.settings.business', compact('settings'));
    }

    public function updateBusinessSettings(Request $request)
    {
        $request->validate([
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
        ]);

        $keys = [
            'business_name',
            'business_description',
            'website_url',
            'email',
            'phone',
            'whatsapp',
            'country',
            'city',
            'address',
            'opening_hours',
            'instagram_url',
            'facebook_url',
            'tiktok_url',
            'whatsapp_url',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''), 'business');
        }

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('settings', 'public');
            Setting::set('logo_url', asset('storage/'.$path), 'business');
        } elseif ($request->filled('logo_url')) {
            Setting::set('logo_url', $request->input('logo_url'), 'business');
        }

        return back()->with('success', 'Business information & logo updated successfully!');
    }

    public function seoSettings()
    {
        $settings = Setting::getAllGroup('seo');

        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeoSettings(Request $request)
    {
        $request->validate([
            'og_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $keys = [
            'site_title',
            'meta_description',
            'default_keywords',
            'canonical_url',
            'robots_setting',
            'gsc_verification_code',
            'google_analytics_id',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''), 'seo');
        }

        if ($request->hasFile('og_image_file')) {
            $path = $request->file('og_image_file')->store('settings', 'public');
            Setting::set('default_og_image', asset('storage/'.$path), 'seo');
        } elseif ($request->filled('default_og_image')) {
            Setting::set('default_og_image', $request->input('default_og_image'), 'seo');
        }

        try {
            $sitemapController = new SitemapController;
            $sitemapController->index();
            $sitemapController->robots();
        } catch (\Throwable $e) {
            // Ignore if error during dynamic update
        }

        return back()->with('success', 'SEO settings & OG image updated successfully!');
    }
}
