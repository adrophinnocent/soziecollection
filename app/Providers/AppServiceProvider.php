<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends AuthServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $features = [
            'dashboard',
            'products',
            'product_stock',
            'product_variants',
            'orders',
            'order_status',
            'customers',
            'marketing',
            'reports',
            'homepage_content',
            'scent_stories',
            'media',
            'reviews',
            'admin_users',
            'settings',
        ];

        foreach ($features as $feature) {
            Gate::define($feature, function (User $user) use ($feature) {
                return $user->canAccess($feature);
            });
        }
    }
}
