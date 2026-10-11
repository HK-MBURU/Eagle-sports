<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'image',
        'image_attribution',
        'category',
        'is_featured',
        'author_name',
        'read_time',
        'published_date',
        'status',
        'slug',
        // SEO fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        // Media field
        'youtube_url',
        // Social media
        'hashtags',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_date' => 'date',
        'read_time' => 'integer',
    ];

    public const CATEGORIES = [
        'NCAA Stories',
        'Athletics',
        'Climate and athletics',
        'Community Projects',
        'Youth Empowerment',
        'Race Results',
        'Coaching Tips',
        'Sports Science',
        'Athlete Health and Mindset',
        'Success Stories',
    ];

    public const STATUSES = [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            if (empty($story->slug)) {
                $story->slug = Str::slug($story->title);
            }
            // Auto-generate meta_title if not provided
            if (empty($story->meta_title)) {
                $story->meta_title = $story->title;
            }
        });

        static::updating(function ($story) {
            if ($story->isDirty('title')) {
                $story->slug = Str::slug($story->title);
            }
        });
    }

    public function likes()
    {
        return $this->hasMany(StoryLike::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereDate('published_date', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('published_date', 'desc');
    }

    public function getFormattedDateAttribute()
    {
        return $this->published_date->format('F d, Y');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-story.jpg');
    }

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image) {
            return asset('storage/' . $this->og_image);
        }
        return $this->image_url;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'published' => 'success',
            'draft' => 'warning',
            'archived' => 'secondary',
            default => 'secondary',
        };
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeVideoIdAttribute()
    {
        if (!$this->youtube_url) return null;

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->youtube_url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * Get YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        $videoId = $this->youtube_video_id;
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }

    /**
     * Get estimated read time based on content
     */
    public function calculateReadTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words/min
        return max(1, $minutes);
    }

    /**
     * Get hashtags as array
     */
    public function getHashtagsArrayAttribute()
    {
        if (!$this->hashtags) return [];

        return array_map(function ($tag) {
            return '#' . trim($tag);
        }, explode(',', $this->hashtags));
    }

    /**
     * Get formatted hashtags string for social media
     */
    public function getFormattedHashtagsAttribute()
    {
        return !empty($this->hashtags_array) ? implode(' ', $this->hashtags_array) : '';
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get parent comments (not replies)
     */
    public function parentComments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    /**
     * Get comments count attribute
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getImageUrlWebpAttribute()
    {
        if ($this->image) {
            $webpPath = pathinfo($this->image, PATHINFO_DIRNAME) . '/' .
                pathinfo($this->image, PATHINFO_FILENAME) . '.webp';

            if (Storage::disk('public')->exists($webpPath)) {
                return asset('storage/' . $webpPath);
            }
        }
        return $this->image_url;
    }

    public function getOgImageUrlWebpAttribute()
    {
        if ($this->og_image) {
            $webpPath = pathinfo($this->og_image, PATHINFO_DIRNAME) . '/' .
                pathinfo($this->og_image, PATHINFO_FILENAME) . '.webp';

            if (Storage::disk('public')->exists($webpPath)) {
                return asset('storage/' . $webpPath);
            }
        }
        return $this->og_image_url;
    }
}
