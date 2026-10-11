<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WomenRunning extends Model
{
    use HasFactory;

    protected $table = 'women_running';

    protected $fillable = [
        'name',
        'title',
        'description',
        'category',
        'image',
        'featured',
        'story_content',
        'video_url',
        'order',
        'status',
        'icon'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'order' => 'integer',
        'status' => 'boolean'
    ];

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Constants for categories
    public const CATEGORY_MENTORSHIP = 'mentorship';
    public const CATEGORY_COACHING = 'coaching';
    public const CATEGORY_ADVOCACY = 'advocacy';
    public const CATEGORY_FEATURED_STORY = 'featured_story';
}