<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'youtube_url',
        'title',
        'description',
        'category',
        'is_featured',
        'added_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Flat slug => name map of active story categories, for validation and legacy callers.
     * Per-request static cache avoids redundant DB hits.
     */
    public static function getCategories(): array
    {
        static $cache = null;
        if ($cache === null) {
            $cache = StoryCategory::active()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->pluck('name', 'slug')
                ->toArray();

            if (empty($cache)) {
                $cache = [
                    'athletics' => 'Athletics', 'football' => 'Football',
                    'rugby' => 'Rugby', 'basketball' => 'Basketball',
                    'volleyball' => 'Volleyball', 'boxing' => 'Boxing',
                    'cricket' => 'Cricket', 'swimming' => 'Swimming',
                    'martial_arts' => 'Martial Arts', 'other' => 'Other',
                ];
            }
        }
        return $cache;
    }

    /**
     * Hierarchical collection of active parent categories with their active children loaded.
     * Used to build <optgroup> selects in create/edit forms.
     */
    public static function getCategoryTree(): \Illuminate\Database\Eloquent\Collection
    {
        return StoryCategory::active()
            ->topLevel()
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('name')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the display name for the category
     */
    public function getCategoryDisplayName(): string
    {
        $cat = StoryCategory::where('slug', $this->category)->first();
        return $cat ? $cat->name : ucwords(str_replace('_', ' ', $this->category));
    }

    /**
     * Extract YouTube video ID from URL
     */
    public function getYoutubeId(): ?string
    {
        $url = $this->youtube_url;

        // Handle various YouTube URL formats
        $patterns = [
            '/^https?:\/\/(?:www\.)?youtube\.com\/watch\?v=([^&]+)/',
            '/^https?:\/\/(?:www\.)?youtu\.be\/([^?]+)/',
            '/^https?:\/\/(?:www\.)?youtube\.com\/embed\/([^\/]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Generate YouTube embed URL
     */
    public function getEmbedUrl(): ?string
    {
        $videoId = $this->getYoutubeId();

        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        return null;
    }

    /**
     * Generate YouTube thumbnail URL
     */
    public function getThumbnailUrl(): ?string
    {
        $videoId = $this->getYoutubeId();

        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        }

        return null;
    }

    /**
     * Check if video is recently added (within last 7 days)
     */
    public function isRecent(): bool
    {
        return $this->created_at->gt(now()->subDays(7));
    }

    /**
     * Scope: Featured videos
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: By category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Recent videos
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Search in title and description
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Relationship: User who added the video
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set the added_by field when creating a new video
        static::creating(function ($video) {
            if (auth()->check() && empty($video->added_by)) {
                $video->added_by = auth()->id();
            }
        });

        // Validate YouTube URL before saving
        static::saving(function ($video) {
            if (!$video->getYoutubeId()) {
                throw new \Exception('Invalid YouTube URL provided.');
            }
        });
    }
}
