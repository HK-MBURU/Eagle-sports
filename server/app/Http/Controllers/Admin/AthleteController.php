<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AthleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $athletes = Athlete::latest()->paginate(12);
        return view('admin.athletes.index', compact('athletes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.athletes.create');
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
            'bio' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('athletes', 'public');
            $validated['image'] = $imagePath;
        }

        // Set defaults
        $validated['is_featured'] = $request->has('is_featured');
        $validated['badge_color'] = $validated['badge_color'] ?? '#FFBF00';

        Athlete::create($validated);

        return redirect()->route('admin.athletes.index')
            ->with('success', 'Athlete created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Athlete $athlete)
    {
        return view('admin.athletes.show', compact('athlete'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Athlete $athlete)
    {
        return view('admin.athletes.edit', compact('athlete'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Athlete $athlete)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'bio' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'badge' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($athlete->image) {
                Storage::disk('public')->delete($athlete->image);
            }
            $imagePath = $request->file('image')->store('athletes', 'public');
            $validated['image'] = $imagePath;
        }

        // Set is_featured
        $validated['is_featured'] = $request->has('is_featured');
        $validated['badge_color'] = $validated['badge_color'] ?? '#FFBF00';

        $athlete->update($validated);

        return redirect()->route('admin.athletes.index')
            ->with('success', 'Athlete updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Athlete $athlete)
    {
        // Delete image from storage
        if ($athlete->image) {
            Storage::disk('public')->delete($athlete->image);
        }

        $athlete->delete();

        return redirect()->route('admin.athletes.index')
            ->with('success', 'Athlete deleted successfully!');
    }
}