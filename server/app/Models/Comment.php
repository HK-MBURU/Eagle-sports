<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'story_id',
        'user_id',
        'parent_id',
        'content',
        'is_edited',
        'edited_at',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $with = ['user']; // Eager load user by default

    /**
     * Get the story that owns the comment
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    /**
     * Get the user that owns the comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies)
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    /**
     * Get all likes for this comment
     */
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Get users who liked this comment
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'comment_likes')->withTimestamps();
    }

    /**
     * Get all reports for this comment
     */
    public function reports()
    {
        return $this->hasMany(CommentReport::class);
    }

    /**
     * Check if a user has liked this comment
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get likes count attribute
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * Get formatted timestamp (e.g., "2 hours ago")
     */
    public function getFormattedTimestampAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if comment is edited
     */
    public function getIsEditedAttribute($value)
    {
        return $value && $this->edited_at !== null;
    }

    /**
     * Scope to get only parent comments (not replies)
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get only replies
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope to get comments for a specific story
     */
    public function scopeForStory($query, $storyId)
    {
        return $query->where('story_id', $storyId);
    }

    /**
     * Scope to get latest comments
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
