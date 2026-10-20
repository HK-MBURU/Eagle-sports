<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StoryCategory extends Model
{
    protected $table = 'story_categories';

    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'color', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function parent()
    {
        return $this->belongsTo(StoryCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(StoryCategory::class, 'parent_id')
                    ->orderBy('sort_order')
                    ->orderBy('name');
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // ── Hooks ────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name);
            }
        });

        static::updating(function (self $cat) {
            if ($cat->isDirty('name')) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }

    public function isSubcategory(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Flat list of active category names (parents + subcategories),
     * used to populate story create/edit dropdowns.
     */
    public static function activeNames(): array
    {
        $all = static::with('parent')
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Sort: top-level categories first, then their children grouped under them
        $parents = $all->whereNull('parent_id')->values();
        $names   = [];

        foreach ($parents as $parent) {
            $names[] = $parent->name;
            foreach ($all->where('parent_id', $parent->id) as $child) {
                $names[] = $child->name;  // child names appear right after their parent
            }
        }

        // Append any orphaned subcategories (edge case)
        foreach ($all->whereNotNull('parent_id') as $child) {
            if (!in_array($child->name, $names)) {
                $names[] = $child->name;
            }
        }

        return $names;
    }
}
