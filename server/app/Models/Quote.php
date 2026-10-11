<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'owner',
        'owner_title',
        'added_by',
    ];

    /**
     * Get the user who added this quote.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the formatted quote with owner details.
     */
    public function getFormattedQuoteAttribute(): string
    {
        $ownerInfo = $this->owner;
        if ($this->owner_title) {
            $ownerInfo .= ', ' . $this->owner_title;
        }
        return "\"{$this->content}\" - {$ownerInfo}";
    }
}
