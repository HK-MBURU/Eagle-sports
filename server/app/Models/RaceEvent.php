<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaceEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'event_date',
        'location',
        'start_time',
        'expected_runners',
        'distance_options',
        'image',
        'description',
        'registration_link',
        'is_featured',
        'is_active',
        'created_by',
        'is_flagship',
        'eyebrow_text',
        'subtitle',
        'coverage_description',
        'art_image_url',
        'art_caption',
        'quick_links',
    ];

    protected $casts = [
        'event_date'  => 'date',
        'start_time'  => 'datetime:H:i',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'is_flagship' => 'boolean',
        'quick_links' => 'array',
    ];

    /**
     * Get the user who created this event
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted date (e.g., "October 15, 2025")
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->event_date->format('F j, Y');
    }

    /**
     * Get formatted time (e.g., "6:00 AM EAT")
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->start_time->format('g:i A') . ' EAT';
    }

    /**
     * Get distance options as array
     */
    public function getDistanceOptionsArrayAttribute(): array
    {
        return explode(', ', $this->distance_options);
    }

    /**
     * Scope for active events
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                     ->orderBy('event_date', 'asc');
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString())
                     ->orderBy('event_date', 'desc');
    }

    /**
     * Scope for the flagship/featured coverage event
     */
    public function scopeFlagship($query)
    {
        return $query->where('is_flagship', true);
    }
}
