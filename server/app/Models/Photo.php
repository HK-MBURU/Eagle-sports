<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $fillable = [
        'title', 'caption', 'alt_text', 'image_path',
        'category', 'source_type', 'source_id',
        'is_featured', 'sort_order', 'status', 'added_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'sort_order'  => 'integer',
        'source_id'   => 'integer',
    ];

    public static function getCategories(): array
    {
        return [
            'athletics' => 'Athletics',
            'events'    => 'Events',
            'community' => 'Community',
            'training'  => 'Training',
            'youth'     => 'Youth Athletics',
            'women'     => 'Women in Sport',
            'nature'    => 'Nature & Climate',
            'other'     => 'Other',
        ];
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /** Full URL to display the photo */
    public function getDisplayUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }
        // Already a full URL (imported from external source)
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }

    public function getCategoryNameAttribute(): string
    {
        return static::getCategories()[$this->category] ?? ucfirst($this->category ?? '');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
