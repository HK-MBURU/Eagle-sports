<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvironmentalInitiative extends Model
{
    use HasFactory;

    protected $table = 'environmental_initiatives';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'stat_value',
        'stat_label',
        'icon_color',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getAll()
    {
        return self::active()
            ->orderBy('display_order')
            ->get();
    }
}
