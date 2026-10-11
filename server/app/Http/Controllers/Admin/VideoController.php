<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index()
    {
        $videos = Video::with('addedBy')
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        $categories = Video::getCategories();
        return view('admin.videos.create', compact('categories'));
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => 'required|url',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', array_keys(Video::getCategories())),
            'is_featured' => 'boolean',
        ]);

        // Set the user who added the video
        $validated['added_by'] = Auth::id();

        // Set default value for is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured');

        try {
            Video::create($validated);

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video added successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding video: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified video.
     */
    public function show(Video $video)
    {
        $video->load('addedBy');
        return view('admin.videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit(Video $video)
    {
        $categories = Video::getCategories();
        return view('admin.videos.edit', compact('video', 'categories'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'youtube_url' => 'required|url',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:' . implode(',', array_keys(Video::getCategories())),
            'is_featured' => 'boolean',
        ]);

        // Set checkbox value
        $validated['is_featured'] = $request->has('is_featured');

        try {
            $video->update($validated);

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating video: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy(Video $video)
    {
        try {
            $video->delete();

            return redirect()->route('admin.videos.index')
                ->with('success', 'Video deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting video: ' . $e->getMessage());
        }
    }

    /**
     * Toggle featured status for a video.
     */
    public function toggleFeatured(Video $video)
    {
        try {
            $video->update([
                'is_featured' => !$video->is_featured
            ]);

            $status = $video->is_featured ? 'featured' : 'unfeatured';

            return redirect()->back()
                ->with('success', "Video {$status} successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating video status: ' . $e->getMessage());
        }
    }

    /**
     * Display videos by category.
     */
    public function byCategory($category)
    {
        if (!array_key_exists($category, Video::getCategories())) {
            return redirect()->route('admin.videos.index')
                ->with('error', 'Invalid category specified.');
        }

        $videos = Video::with('addedBy')
            ->byCategory($category)
            ->latest()
            ->paginate(15);

        $categoryName = Video::getCategories()[$category];

        return view('admin.videos.index', compact('videos', 'categoryName'));
    }

    /**
     * Display featured videos.
     */
    public function featured()
    {
        $videos = Video::with('addedBy')
            ->featured()
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos'))->with('featuredView', true);
    }

    /**
     * Search videos.
     */
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2'
        ]);

        $searchTerm = $request->input('search');

        $videos = Video::with('addedBy')
            ->search($searchTerm)
            ->latest()
            ->paginate(15);

        return view('admin.videos.index', compact('videos', 'searchTerm'));
    }
}
