<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaceEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaceEventController extends Controller
{
    /**
     * Display a listing of the race events.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = RaceEvent::with('creator');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        match ($filter) {
            'upcoming' => $query->where('event_date', '>=', now()->toDateString())->orderBy('event_date'),
            'past'     => $query->where('event_date', '<',  now()->toDateString())->orderByDesc('event_date'),
            'featured' => $query->where('is_featured', true)->latest(),
            'flagship' => $query->where('is_flagship', true)->latest(),
            default    => $query->latest(),
        };

        $events = $query->paginate(12)->withQueryString();

        $stats = [
            'total'    => RaceEvent::count(),
            'upcoming' => RaceEvent::where('event_date', '>=', now()->toDateString())->count(),
            'past'     => RaceEvent::where('event_date', '<',  now()->toDateString())->count(),
            'flagship' => RaceEvent::where('is_flagship', true)->count(),
        ];

        return view('admin.race-events.index', compact('events', 'filter', 'search', 'stats'));
    }

    /**
     * Show the form for creating a new race event.
     */
    public function create()
    {
        return view('admin.race-events.create');
    }

    /**
     * Store a newly created race event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'expected_runners' => 'nullable|string|max:255',
            'distance_options' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'registration_link' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('race-events', 'public');
        }

        // Set the creator
        $validated['created_by'] = auth()->id();

        // Set default values for checkboxes
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : true; // Default to true

        RaceEvent::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Race event created successfully.');
    }

    /**
     * Display the specified race event.
     */
    public function show(RaceEvent $event)
    {
        $event->load('creator');
        return view('admin.race-events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified race event.
     */
    public function edit(RaceEvent $event)
    {
        return view('admin.race-events.edit', compact('event'));
    }

    /**
     * Update the specified race event in storage.
     */
    public function update(Request $request, RaceEvent $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'expected_runners' => 'nullable|string|max:255',
            'distance_options' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'registration_link' => 'nullable|url|max:255',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('race-events', 'public');
        }

        // Set checkbox values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Race event updated successfully.');
    }

    /**
     * Remove the specified race event from storage.
     */
    public function destroy(RaceEvent $event)
    {
        // Delete associated image if exists
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Race event deleted successfully.');
    }
}
