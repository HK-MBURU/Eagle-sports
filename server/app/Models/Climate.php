<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Climate extends Model
{
    use HasFactory;

    // Add this line to specify the correct table name
    protected $table = 'climate_contents';

    protected $fillable = [
        'title',
        'description',
        'image_url',
        'stat_1_value',
        'stat_1_label',
        'stat_2_value',
        'stat_2_label',
        'cta_text',
        'cta_url',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope to get only active content
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get the featured climate content
    public static function getFeatured()
    {
        return self::active()
            ->orderBy('display_order')
            ->first();
    }
}
