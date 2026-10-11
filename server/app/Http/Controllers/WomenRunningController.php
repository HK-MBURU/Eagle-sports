<?php

namespace App\Http\Controllers;

use App\Models\WomenRunning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WomenRunningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $womenRunning = WomenRunning::latest()->paginate(10);
        return view('admin.women_running.index', compact('womenRunning'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'mentorship' => 'Mentorship',
            'coaching' => 'Coaching',
            'advocacy' => 'Advocacy',
            'featured_story' => 'Featured Story'
        ];

        $icons = [
            'circle' => 'Circle',
            'users' => 'Users',
            'star' => 'Star',
            'trophy' => 'Trophy',
            'heart' => 'Heart',
            'medal' => 'Medal'
        ];

        return view('admin.women_running.create', compact('categories', 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:mentorship,coaching,advocacy,featured_story',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:50',
            'featured' => 'nullable|boolean',
            'story_content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('women-running', 'public');
        }

        // Set defaults
        $validated['featured'] = $request->has('featured') ? true : false;
        $validated['status'] = $request->has('status') ? true : false;
        $validated['order'] = $validated['order'] ?? 0;

        WomenRunning::create($validated);

        return redirect()->route('admin.women-running.index')
            ->with('success', 'Entry created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WomenRunning $womenRunning)
    {
        return view('admin.women_running.show', compact('womenRunning'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WomenRunning $womenRunning)
    {
        $categories = [
            'mentorship' => 'Mentorship',
            'coaching' => 'Coaching',
            'advocacy' => 'Advocacy',
            'featured_story' => 'Featured Story'
        ];

        $icons = [
            'circle' => 'Circle',
            'users' => 'Users',
            'star' => 'Star',
            'trophy' => 'Trophy',
            'heart' => 'Heart',
            'medal' => 'Medal'
        ];

        return view('admin.women_running.edit', compact('womenRunning', 'categories', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WomenRunning $womenRunning)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:mentorship,coaching,advocacy,featured_story',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:50',
            'featured' => 'nullable|boolean',
            'story_content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($womenRunning->image) {
                Storage::disk('public')->delete($womenRunning->image);
            }
            $validated['image'] = $request->file('image')->store('women-running', 'public');
        }

        // Set boolean values
        $validated['featured'] = $request->has('featured') ? true : false;
        $validated['status'] = $request->has('status') ? true : false;

        $womenRunning->update($validated);

        return redirect()->route('admin.women-running.index')
            ->with('success', 'Entry updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WomenRunning $womenRunning)
    {
        // Delete image if exists
        if ($womenRunning->image) {
            Storage::disk('public')->delete($womenRunning->image);
        }

        $womenRunning->delete();

        return redirect()->route('admin.women-running.index')
            ->with('success', 'Entry deleted successfully!');
    }

    /**
     * Toggle status
     */
    public function toggleStatus(WomenRunning $womenRunning)
    {
        $womenRunning->update(['status' => !$womenRunning->status]);

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Toggle featured
     */
    public function toggleFeatured(WomenRunning $womenRunning)
    {
        $womenRunning->update(['featured' => !$womenRunning->featured]);

        return back()->with('success', 'Featured status updated successfully!');
    }
}