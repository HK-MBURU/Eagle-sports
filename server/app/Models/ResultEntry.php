<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultEntry extends Model
{
    protected $fillable = [
        'competition',
        'event_discipline',
        'performance',
        'athlete_name',
        'athlete_country',
        'series',
        'result_status',
        'race_date',
        'location',
        'is_featured',
        'is_published',
        'created_by',
    ];

    protected $casts = [
        'race_date'    => 'date',
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
