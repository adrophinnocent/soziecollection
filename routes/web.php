<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\HomepageSlideController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\PathTraversalDetected;

Route::get('/storage/{path}', function (string $path) {
    $disk = Storage::disk('public');

    try {
        abort_unless($disk->exists($path), 404);
    } catch (PathTraversalDetected) {
        abort(404);
    }

    return $disk->response($path, headers: [
        'Cache-Control' => 'public, max-age=31536000, immutable',
        'Content-Security-Policy' => "default-src 'none'; sandbox",
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->where('path', '.*')->name('storage.public');

Route::get('/lang/{locale}', function (string $locale) {
    $locale = strtolower($locale);
    $availableLocales = config('app.available_locales', ['en', 'sw']);

    abort_unless(in_array($locale, $availableLocales, true), 404);

    session(['locale' => $locale]);

    return redirect()->back(fallback: route('home'));
})->name('lang.switch');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('shop.show');
Route::post('/product/{id}/review', [ProductController::class, 'storeReview'])->name('product.review');
Route::get('/api/fragrance-finder', [ProductController::class, 'fragranceFinder'])->name('api.fragrance_finder');
Route::get('/api/product/{id}/quickview', [ProductController::class, 'quickView'])->name('api.product_quickview');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/api/cart', [CartController::class, 'getCartApi'])->name('api.cart');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/order/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/order/{order}/share', [OrderController::class, 'share'])
    ->middleware('signed')
    ->name('orders.share');
Route::get('/track-order', [OrderController::class, 'track'])->name('orders.track');
Route::post('/track-order', [OrderController::class, 'handleTrack'])->name('orders.track.submit');

Route::get('/wishlist', function () {
    if (Auth::check()) {
        return redirect()->route('account.wishlist');
    }

    return redirect()->route('login')->with('info', __('Please login to manage your wishlist across devices.'));
})->name('wishlist.public');

Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/forgot-password', [CustomerAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [CustomerAuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [CustomerAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword'])->name('password.store');

Route::get('/account/redirect', [CustomerAuthController::class, 'showAccountRedirect'])->name('account.redirect');

Route::prefix('account')->name('account.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');

    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'showOrder'])->name('orders.show');

    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');

    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::get('/addresses/create', [AccountController::class, 'createAddress'])->name('addresses.create');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/addresses/{id}/edit', [AccountController::class, 'editAddress'])->name('addresses.edit');
    Route::put('/addresses/{id}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{id}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::patch('/addresses/{id}/default', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');

    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AccountController::class, 'updatePassword'])->name('profile.password');
});

Route::prefix('api/wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [WishlistController::class, 'indexApi'])->name('index_api');
    Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    Route::post('/remove', [WishlistController::class, 'remove'])->name('remove');
    Route::post('/move-to-cart', [WishlistController::class, 'moveToCart'])->name('move_to_cart');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::middleware('can:products')->group(function () {
            Route::get('/products', [AdminController::class, 'products'])->name('products');
            Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
            Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
            Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
            Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
            Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        });

        Route::middleware('can:orders')->group(function () {
            Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
            Route::patch('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
        });

        Route::middleware('can:customers')->group(function () {
            Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
        });

        Route::middleware('can:marketing')->group(function () {
            Route::get('/marketing', [AdminController::class, 'marketing'])->name('marketing');
        });

        Route::middleware('can:reports')->group(function () {
            Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        });

        Route::middleware('can:homepage_content')->group(function () {
            Route::get('/content', [AdminController::class, 'content'])->name('content');
            Route::post('/content/slides', [HomepageSlideController::class, 'store'])->name('slides.store');
            Route::put('/content/slides/{banner}', [HomepageSlideController::class, 'update'])->name('slides.update');
            Route::delete('/content/slides/{banner}', [HomepageSlideController::class, 'destroy'])->name('slides.destroy');
        });

        Route::middleware('can:admin_users')->group(function () {
            Route::get('/users', [AdminController::class, 'adminUsers'])->name('users');
        });

        Route::middleware('can:settings')->group(function () {
            Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
        });
    });
});

Route::fallback(function () {
    return redirect()->route('home');
});
