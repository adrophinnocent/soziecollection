<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'price',
        'discount_price',
        'sku',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_available' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->discount_price ?: $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return 'TZS '.number_format($this->price, 0, '.', ',');
    }

    public function getFormattedEffectivePriceAttribute()
    {
        return 'TZS '.number_format($this->effective_price, 0, '.', ',');
    }
}
