<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
    ];

    /**
     * Get the user who liked
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the story that was liked
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
