<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'city',
        'shipping_address',
        'notes',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'payment_method',
        'whatsapp_ordered',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'whatsapp_ordered' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function getFormattedTotalAttribute()
    {
        return 'TZS '.number_format($this->total_amount, 0, '.', ',');
    }

    /**
     * Generate the next sequential order number.
     *
     * Format: SOZ-YYYYMMDD-NNN (e.g., SOZ-20260924-001)
     */
    public static function generateNextOrderNumber(?Carbon $date = null): string
    {
        $date = $date ?? Carbon::now();
        $prefix = 'SOZ-'.$date->format('Ymd').'-';

        $lastOrder = self::where('order_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->first();

        if (! $lastOrder) {
            return $prefix.'001';
        }

        $suffix = (int) str_replace($prefix, '', $lastOrder->order_number);

        return $prefix.str_pad((string) ($suffix + 1), 3, '0', STR_PAD_LEFT);
    }

    /**
     * Human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => __('order.status.new'),
            'confirmed' => __('order.status.confirmed'),
            'processing' => __('order.status.processing'),
            'shipped' => __('order.status.shipped'),
            'delivered' => __('order.status.delivered'),
            'cancelled' => __('order.status.cancelled'),
            default => ucfirst((string) $this->status),
        };
    }

    /**
     * Human-readable payment method label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $key = match ((string) $this->payment_method) {
            'whatsapp' => 'order.payment_method.whatsapp',
            'mobile_money' => 'order.payment_method.mobile_money',
            'bank_transfer' => 'order.payment_method.bank_transfer',
            'cash_on_delivery' => 'order.payment_method.cash_on_delivery',
            default => null,
        };

        if ($key === null) {
            return ucfirst(str_replace('_', ' ', (string) $this->payment_method));
        }

        return __($key);
    }

    /**
     * Color class for status badge.
     *
     * @return array{bg: string, text: string, ring: string}
     */
    public function getStatusBadgeClassAttribute(): array
    {
        return match ($this->status) {
            'new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'ring' => 'ring-blue-600/20'],
            'confirmed' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'ring' => 'ring-purple-600/20'],
            'processing' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'ring' => 'ring-amber-600/20'],
            'shipped' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'ring' => 'ring-indigo-600/20'],
            'delivered' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'ring' => 'ring-emerald-600/20'],
            'cancelled' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'ring' => 'ring-rose-600/20'],
            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'ring' => 'ring-gray-600/20'],
        };
    }
}
