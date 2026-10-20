<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\StoryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoryCategoryController extends Controller
{
    public function index()
    {
        // Single queries for story counts
        $totals    = Story::selectRaw('category, COUNT(*) as total')
                          ->groupBy('category')->pluck('total', 'category');
        $published = Story::published()
                          ->selectRaw('category, COUNT(*) as total')
                          ->groupBy('category')->pluck('total', 'category');

        // Load parents with their children eager-loaded
        $parents = StoryCategory::with(['children' => function ($q) use ($totals, $published) {
            $q->orderBy('sort_order')->orderBy('name');
        }])
        ->topLevel()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->map(function ($cat) use ($totals, $published) {
            $cat->stories_total     = $totals[$cat->name]    ?? 0;
            $cat->stories_published = $published[$cat->name] ?? 0;
            $cat->children->each(function ($child) use ($totals, $published) {
                $child->stories_total     = $totals[$child->name]    ?? 0;
                $child->stories_published = $published[$child->name] ?? 0;
            });
            return $cat;
        });

        // Orphaned subcategories (parent was deleted, nullOnDelete left them)
        $orphans = StoryCategory::whereNotNull('parent_id')
            ->whereDoesntHave('parent')
            ->orderBy('name')->get()
            ->map(function ($cat) use ($totals, $published) {
                $cat->stories_total     = $totals[$cat->name]    ?? 0;
                $cat->stories_published = $published[$cat->name] ?? 0;
                return $cat;
            });

        $allCount      = StoryCategory::count();
        $subCount      = StoryCategory::whereNotNull('parent_id')->count();
        $activeCount   = StoryCategory::where('is_active', true)->count();
        $coveredCount  = $totals->sum();

        $stats = [
            'total'      => $allCount,
            'top_level'  => $allCount - $subCount,
            'sub'        => $subCount,
            'active'     => $activeCount,
            'covered'    => $coveredCount,
        ];

        // Top-level categories for the parent selector in modals
        $topLevelOptions = StoryCategory::topLevel()->orderBy('sort_order')->orderBy('name')->get(['id', 'name', 'color']);

        return view('admin.story-categories.index', compact(
            'parents', 'orphans', 'stats', 'topLevelOptions'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id'   => 'nullable|integer|exists:story_categories,id',
            'name'        => 'required|string|max:100|unique:story_categories,name',
            'description' => 'nullable|string|max:500',
            'color'       => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sort_order'  => 'nullable|integer|min:0|max:9999',
        ]);

        $cat = StoryCategory::create([
            'parent_id'   => $data['parent_id'] ?? null,
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'color'       => $data['color'] ?? '#6366f1',
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => true,
        ]);

        $type = $cat->parent_id ? 'Subcategory' : 'Category';
        return redirect()->route('admin.story-categories.index')
            ->with('success', "{$type} \"{$cat->name}\" created successfully.");
    }

    public function update(Request $request, StoryCategory $storyCategory)
    {
        $data = $request->validate([
            'parent_id'   => [
                'nullable', 'integer',
                Rule::exists('story_categories', 'id'),
                // Prevent setting self as parent or creating cycles
                function ($attribute, $value, $fail) use ($storyCategory) {
                    if ($value && (int) $value === $storyCategory->id) {
                        $fail('A category cannot be its own parent.');
                    }
                    if ($value) {
                        $child = StoryCategory::find($value);
                        if ($child && $child->parent_id === $storyCategory->id) {
                            $fail('Cannot create circular parent-child relationship.');
                        }
                    }
                },
            ],
            'name'        => ['required', 'string', 'max:100',
                              Rule::unique('story_categories', 'name')->ignore($storyCategory->id)],
            'description' => 'nullable|string|max:500',
            'color'       => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'sort_order'  => 'nullable|integer|min:0|max:9999',
            'is_active'   => 'nullable|boolean',
        ]);

        $oldName = $storyCategory->name;

        $storyCategory->update([
            'parent_id'   => $data['parent_id'] ?? null,
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'color'       => $data['color'] ?? $storyCategory->color,
            'sort_order'  => $data['sort_order'] ?? $storyCategory->sort_order,
            'is_active'   => $request->boolean('is_active', $storyCategory->is_active),
        ]);

        // Cascade rename to all stories tagged with old name
        $renamed = 0;
        if ($data['name'] !== $oldName) {
            $renamed = Story::where('category', $oldName)->update(['category' => $data['name']]);
        }

        $msg = 'Category updated.';
        if ($renamed > 0) {
            $msg .= " {$renamed} " . ($renamed === 1 ? 'story' : 'stories') . ' re-tagged.';
        }

        return redirect()->route('admin.story-categories.index')->with('success', $msg);
    }

    public function destroy(StoryCategory $storyCategory)
    {
        // Check stories on this category
        $ownStories = Story::where('category', $storyCategory->name)->count();
        if ($ownStories > 0) {
            return redirect()->route('admin.story-categories.index')
                ->with('error', "Cannot delete \"{$storyCategory->name}\" — {$ownStories} "
                    . ($ownStories === 1 ? 'story uses' : 'stories use')
                    . ' this category. Reassign them first.');
        }

        // Check stories on its subcategories
        $children        = $storyCategory->children()->get();
        $childStoryCount = 0;
        foreach ($children as $child) {
            $childStoryCount += Story::where('category', $child->name)->count();
        }
        if ($childStoryCount > 0) {
            return redirect()->route('admin.story-categories.index')
                ->with('error', "Cannot delete \"{$storyCategory->name}\" — its subcategories have {$childStoryCount} "
                    . ($childStoryCount === 1 ? 'story' : 'stories')
                    . ' assigned. Reassign those stories first.');
        }

        // Safe to delete; children will have parent_id nulled (nullOnDelete FK)
        $name         = $storyCategory->name;
        $childCount   = $children->count();
        $storyCategory->delete();

        $msg = "Category \"{$name}\" deleted.";
        if ($childCount > 0) {
            $msg .= " {$childCount} " . ($childCount === 1 ? 'subcategory was' : 'subcategories were')
                  . ' promoted to top-level.';
        }

        return redirect()->route('admin.story-categories.index')->with('success', $msg);
    }

    public function toggleActive(StoryCategory $storyCategory)
    {
        $storyCategory->update(['is_active' => !$storyCategory->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $storyCategory->is_active,
        ]);
    }
}
