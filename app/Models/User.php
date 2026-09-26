<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    final public const ROLE_CUSTOMER = 'customer';

    final public const ROLE_CONTENT_MANAGER = 'content_manager';

    final public const ROLE_INVENTORY_MANAGER = 'inventory_manager';

    final public const ROLE_ORDER_MANAGER = 'order_manager';

    final public const ROLE_MANAGER = 'manager';

    final public const ROLE_SUPER_ADMIN = 'super_admin';

    /**
     * Role permissions matrix.
     *
     * Feature keys: dashboard, products, product_stock, product_variants,
     * orders, order_status, customers, marketing, reports,
     * homepage_content, scent_stories, media, reviews, admin_users, settings
     *
     * @return array<string, array<string>>
     */
    public static function rolePermissions(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => [
                'dashboard', 'products', 'product_stock', 'product_variants',
                'orders', 'order_status', 'customers', 'marketing', 'reports',
                'homepage_content', 'scent_stories', 'media', 'reviews',
                'admin_users', 'settings',
            ],
            self::ROLE_MANAGER => [
                'dashboard', 'products', 'product_stock', 'product_variants',
                'orders', 'order_status', 'customers', 'marketing', 'reports',
            ],
            self::ROLE_INVENTORY_MANAGER => [
                'dashboard', 'products', 'product_stock', 'product_variants',
            ],
            self::ROLE_ORDER_MANAGER => [
                'dashboard', 'orders', 'order_status', 'customers',
            ],
            self::ROLE_CONTENT_MANAGER => [
                'dashboard', 'homepage_content', 'scent_stories', 'media', 'reviews',
            ],
            self::ROLE_CUSTOMER => [],
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    /**
     * Check if the user has one of the admin roles.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_INVENTORY_MANAGER,
            self::ROLE_ORDER_MANAGER,
            self::ROLE_CONTENT_MANAGER,
        ], true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user can access a specific admin feature.
     */
    public function canAccess(string $feature): bool
    {
        $permissions = self::rolePermissions()[$this->role] ?? [];

        return in_array($feature, $permissions, true);
    }

    /**
     * Scope for admin users only.
     */
    public function scopeAdmins(Builder $query): Builder
    {
        return $query->whereIn('role', [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_INVENTORY_MANAGER,
            self::ROLE_ORDER_MANAGER,
            self::ROLE_CONTENT_MANAGER,
        ]);
    }

    /**
     * Return human-readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => __('Super Admin'),
            self::ROLE_MANAGER => __('Manager'),
            self::ROLE_INVENTORY_MANAGER => __('Inventory Manager'),
            self::ROLE_ORDER_MANAGER => __('Order Manager'),
            self::ROLE_CONTENT_MANAGER => __('Content Manager'),
            default => __('Customer'),
        };
    }
}
