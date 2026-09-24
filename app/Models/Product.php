<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'product_type',
        'gender',
        'price',
        'discount_price',
        'discount_percentage',
        'stock_quantity',
        'is_available',
        'is_best_seller',
        'is_new_arrival',
        'is_featured',
        'is_limited_edition',
        'description',
        'why_you_will_love_it',
        'fragrance_family',
        'scent_type',
        'top_notes',
        'heart_notes',
        'base_notes',
        'concentration',
        'default_size',
        'longevity',
        'occasion',
        'season',
        'time_of_day',
        'intensity',
        'sillage',
        'images',
        'campaign_image',
        'video_url',
        'ad_headline',
        'ad_copy',
        'ad_cta',
        'instagram_caption',
        'facebook_caption',
        'tiktok_caption',
        'whatsapp_caption',
        'seo_title',
        'meta_description',
        'image_alt',
    ];

    protected $casts = [
        'why_you_will_love_it' => 'array',
        'images' => 'array',
        'is_available' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured' => 'boolean',
        'is_limited_edition' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->discount_price ?: $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return 'TZS '.number_format($this->effective_price, 0, '.', ',');
    }

    public function getFormattedOriginalPriceAttribute()
    {
        return 'TZS '.number_format($this->price, 0, '.', ',');
    }

    public function getPrimaryImageAttribute()
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }

        return $this->campaign_image ?: 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&q=80&w=800';
    }

    public function getEmbedVideoUrlAttribute()
    {
        if (! $this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return $this->video_url;
    }
}
