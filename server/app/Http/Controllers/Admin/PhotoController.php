<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Story;
use App\Models\WomenRunning;
use App\Models\RaceEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index(Request $request)
    {
        $query = Photo::with('addedBy')->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('source_type')) {
            $query->where('source_type', $request->source_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $photos     = $query->paginate(24)->withQueryString();
        $categories = Photo::getCategories();

        return view('admin.photos.index', compact('photos', 'categories'));
    }

    public function create()
    {
        $categories = Photo::getCategories();
        return view('admin.photos.create', compact('categories'));
    }

    /** AJAX: return images available for import from existing content */
    public function sourceImages(Request $request)
    {
        $source = $request->get('source', 'story');
        $page   = (int) $request->get('page', 1);
        $limit  = 18;
        $offset = ($page - 1) * $limit;

        $items = [];

        if ($source === 'story') {
            $rows = Story::whereNotNull('image')
                ->where('image', '!=', '')
                ->select('id', 'title', 'image', 'category')
                ->latest()
                ->skip($offset)->take($limit + 1)->get();

            foreach ($rows->take($limit) as $r) {
                $items[] = [
                    'id'         => $r->id,
                    'title'      => $r->title,
                    'image_path' => $r->image,
                    'thumb_url'  => asset('storage/' . $r->image),
                    'category'   => $r->category ?? 'athletics',
                    'source_type'=> 'story',
                ];
            }
            $hasMore = $rows->count() > $limit;

        } elseif ($source === 'women_running') {
            $rows = WomenRunning::whereNotNull('image')
                ->where('image', '!=', '')
                ->select('id', 'name', 'image')
                ->latest()
                ->skip($offset)->take($limit + 1)->get();

            foreach ($rows->take($limit) as $r) {
                $items[] = [
                    'id'         => $r->id,
                    'title'      => $r->name,
                    'image_path' => $r->image,
                    'thumb_url'  => asset('storage/' . $r->image),
                    'category'   => 'women',
                    'source_type'=> 'women_running',
                ];
            }
            $hasMore = $rows->count() > $limit;

        } elseif ($source === 'race_event') {
            $rows = RaceEvent::whereNotNull('image')
                ->where('image', '!=', '')
                ->select('id', 'name', 'image')
                ->latest()
                ->skip($offset)->take($limit + 1)->get();

            foreach ($rows->take($limit) as $r) {
                $items[] = [
                    'id'         => $r->id,
                    'title'      => $r->name,
                    'image_path' => $r->image,
                    'thumb_url'  => asset('storage/' . $r->image),
                    'category'   => 'events',
                    'source_type'=> 'race_event',
                ];
            }
            $hasMore = $rows->count() > $limit;

        } else {
            $hasMore = false;
        }

        return response()->json(['items' => $items, 'has_more' => $hasMore]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'caption'     => 'nullable|string|max:1000',
            'alt_text'    => 'nullable|string|max:255',
            'category'    => 'required|string|in:' . implode(',', array_keys(Photo::getCategories())),
            'status'      => 'required|in:published,draft',
            'is_featured' => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',

            // For direct upload
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',

            // For source import
            'source_type' => 'nullable|in:story,women_running,race_event',
            'source_id'   => 'nullable|integer',
            'source_path' => 'nullable|string|max:500',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['added_by']    = Auth::id();

        try {
            if ($request->hasFile('image')) {
                $validated['image_path'] = $request->file('image')
                    ->store('photos', 'public');
                $validated['source_type'] = 'upload';
            } elseif ($request->filled('source_path')) {
                $validated['image_path'] = $request->source_path;
                $validated['source_type'] = $request->source_type ?? 'story';
                $validated['source_id']   = $request->source_id;
            } else {
                return back()->withInput()->with('error', 'Please upload an image or select one from existing content.');
            }

            Photo::create($validated);

            return redirect()->route('admin.photos.index')
                ->with('success', 'Photo added to gallery successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error saving photo: ' . $e->getMessage());
        }
    }

    /** Batch import — POST multiple source images at once */
    public function batchImport(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1|max:50',
            'items.*.title'      => 'required|string|max:255',
            'items.*.image_path' => 'required|string|max:500',
            'items.*.source_type'=> 'required|in:story,women_running,race_event',
            'items.*.source_id'  => 'nullable|integer',
            'items.*.category'   => 'nullable|string',
            'category_override'  => 'nullable|string|in:' . implode(',', array_keys(Photo::getCategories())),
        ]);

        $count = 0;
        foreach ($request->items as $item) {
            // Skip if this exact source image is already in the gallery
            $exists = Photo::where('source_type', $item['source_type'])
                ->where('source_id', $item['source_id'] ?? null)
                ->where('image_path', $item['image_path'])
                ->exists();

            if ($exists) continue;

            Photo::create([
                'title'       => $item['title'],
                'image_path'  => $item['image_path'],
                'category'    => $request->category_override ?: ($item['category'] ?? 'other'),
                'source_type' => $item['source_type'],
                'source_id'   => $item['source_id'] ?? null,
                'status'      => 'published',
                'is_featured' => false,
                'added_by'    => Auth::id(),
            ]);
            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "$count photo(s) added to gallery.",
        ]);
    }

    public function edit(Photo $photo)
    {
        $categories = Photo::getCategories();
        return view('admin.photos.edit', compact('photo', 'categories'));
    }

    public function update(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'caption'     => 'nullable|string|max:1000',
            'alt_text'    => 'nullable|string|max:255',
            'category'    => 'required|string|in:' . implode(',', array_keys(Photo::getCategories())),
            'status'      => 'required|in:published,draft',
            'is_featured' => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        try {
            if ($request->hasFile('image')) {
                // Delete old if it was a direct upload
                if ($photo->source_type === 'upload' && $photo->image_path) {
                    Storage::disk('public')->delete($photo->image_path);
                }
                $validated['image_path']  = $request->file('image')->store('photos', 'public');
                $validated['source_type'] = 'upload';
            }

            $photo->update($validated);

            return redirect()->route('admin.photos.index')
                ->with('success', 'Photo updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating photo: ' . $e->getMessage());
        }
    }

    public function destroy(Photo $photo)
    {
        try {
            if ($photo->source_type === 'upload' && $photo->image_path) {
                Storage::disk('public')->delete($photo->image_path);
            }
            $photo->delete();
            return redirect()->route('admin.photos.index')
                ->with('success', 'Photo removed from gallery.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting photo: ' . $e->getMessage());
        }
    }

    public function toggleFeatured(Photo $photo)
    {
        $photo->update(['is_featured' => !$photo->is_featured]);
        $status = $photo->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Photo {$status} successfully.");
    }
}
