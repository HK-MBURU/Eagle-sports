<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdminNotification extends Model
{
    protected $fillable = ['type', 'title', 'message', 'data', 'link', 'read_at'];

    protected $casts = [
        'data'    => 'array',
        'read_at' => 'datetime',
    ];

    // Stale thresholds in days
    public const STALE_THRESHOLDS = [
        'stale_stories'       => ['model' => Story::class,        'days' => 7,  'label' => 'Stories',            'link' => '/admin/stories/create'],
        'stale_events'        => ['model' => RaceEvent::class,    'days' => 30, 'label' => 'Race Events',         'link' => '/admin/events/create'],
        'stale_videos'        => ['model' => Video::class,        'days' => 30, 'label' => 'Videos',              'link' => '/admin/videos/create'],
        'stale_women_running' => ['model' => WomenRunning::class, 'days' => 30, 'label' => 'Women Running',       'link' => '/admin/women-running/create'],
        'stale_performance'   => ['model' => ResultEntry::class,  'days' => 14, 'label' => 'Performance Entries', 'link' => '/admin/result-entries/create'],
        'stale_climate'       => ['model' => Climate::class,      'days' => 30, 'label' => 'Climate',             'link' => '/admin/climate/create'],
    ];

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function scopeUnread(Builder $q): Builder
    {
        return $q->whereNull('read_at');
    }

    public function scopeLatestFirst(Builder $q): Builder
    {
        return $q->orderByDesc('created_at');
    }

    public function markRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    public static function notify(string $type, string $title, string $message, array $data = [], ?string $link = null): self
    {
        return self::create(compact('type', 'title', 'message', 'data', 'link'));
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            $this->type === 'new_comment' => 'chat-dots-fill',
            $this->type === 'new_user'    => 'person-plus-fill',
            str_starts_with($this->type, 'stale_') => 'exclamation-triangle-fill',
            default => 'bell-fill',
        };
    }

    public function getColorAttribute(): string
    {
        return match (true) {
            $this->type === 'new_comment' => '#2563eb',
            $this->type === 'new_user'    => '#16a34a',
            str_starts_with($this->type, 'stale_') => '#d97706',
            default => '#6b7280',
        };
    }

    public function getBgAttribute(): string
    {
        return match (true) {
            $this->type === 'new_comment' => '#eff6ff',
            $this->type === 'new_user'    => '#f0fdf4',
            str_starts_with($this->type, 'stale_') => '#fffbeb',
            default => '#f9fafb',
        };
    }
}
