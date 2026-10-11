<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Optional: Add helper methods for roles
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function isGeneral(): bool
    {
        return $this->role === 'general';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function likedStories()
    {
        return $this->belongsToMany(Story::class, 'story_likes')->withTimestamps();
    }

    /**
     * Get all likes by this user
     */
    public function likes()
    {
        return $this->hasMany(StoryLike::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all comment likes by this user
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Get all comments reported by this user
     */
    public function reportedComments()
    {
        return $this->hasMany(CommentReport::class, 'reported_by');
    }

    public function raceResults()
    {
        return $this->hasMany(Result::class, 'uploaded_by');
    }
}
