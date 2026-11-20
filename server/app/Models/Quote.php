<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'owner',
        'owner_title',
        'owner_image',
        'added_by',
    ];

    protected $appends = ['owner_image_url'];

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getOwnerImageUrlAttribute(): ?string
    {
        return $this->owner_image ? url('/images/' . $this->owner_image) : null;
    }

    public function getFormattedQuoteAttribute(): string
    {
        $ownerInfo = $this->owner;
        if ($this->owner_title) {
            $ownerInfo .= ', ' . $this->owner_title;
        }
        return "\"{$this->content}\" - {$ownerInfo}";
    }
}
