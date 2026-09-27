<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'eyebrow',
        'headline',
        'highlight_text',
        'subtitle',
        'image',
        'mobile_image',
        'button_text',
        'button_link',
        'secondary_button_text',
        'secondary_button_link',
        'is_active',
        'show_in_hero',
        'show_in_gallery',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_hero' => 'boolean',
        'show_in_gallery' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForHero(Builder $query): Builder
    {
        return $query->where('show_in_hero', true);
    }

    public function scopeForGallery(Builder $query): Builder
    {
        return $query->where('show_in_gallery', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->mobile_image);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
