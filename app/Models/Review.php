<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'rating',
        'comment',
        'is_verified',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
