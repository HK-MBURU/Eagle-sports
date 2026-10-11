<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RaceEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RaceEventApiController extends Controller
{
    /**
     * Display a listing of the race events for API consumption.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $featured = $request->get('featured');
            $active = $request->get('active', true);
            $upcoming = $request->get('upcoming');
            $search = $request->get('search');

            $query = RaceEvent::with('creator')
                ->select([
                    'id',
                    'title',
                    'event_date',
                    'location',
                    'start_time',
                    'expected_runners',
                    'distance_options',
                    'image',
                    'description',
                    'registration_link',
                    'is_featured',
                    'is_active',
                    'created_by',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by featured events
            if ($featured !== null) {
                $query->where('is_featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter by active events
            if ($active !== null) {
                $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter upcoming events
            if ($upcoming !== null) {
                $now = now();
                if (filter_var($upcoming, FILTER_VALIDATE_BOOLEAN)) {
                    $query->where('event_date', '>=', $now->format('Y-m-d'));
                } else {
                    $query->where('event_date', '<', $now->format('Y-m-d'));
                }
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Order by event date (upcoming first, then by creation date)
            $query->orderBy('event_date', 'asc')
                  ->orderBy('created_at', 'desc');

            $events = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform the data to include full image URLs
            $events->getCollection()->transform(function ($event) {
                return $this->transformEvent($event);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $events->items(),
                    'pagination' => [
                        'current_page' => $events->currentPage(),
                        'last_page' => $events->lastPage(),
                        'per_page' => $events->perPage(),
                        'total' => $events->total(),
                        'from' => $events->firstItem(),
                        'to' => $events->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch race events',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display featured race events for API consumption.
     */
    public function featured(): JsonResponse
    {
        try {
            $events = RaceEvent::with('creator')
                ->where('is_featured', true)
                ->where('is_active', true)
                ->where('event_date', '>=', now()->format('Y-m-d'))
                ->orderBy('event_date', 'asc')
                ->limit(6)
                ->get()
                ->map(function ($event) {
                    return $this->transformEvent($event);
                });

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured events',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display upcoming race events for API consumption.
     */
    public function upcoming(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);

            $events = RaceEvent::with('creator')
                ->where('is_active', true)
                ->where('event_date', '>=', now()->format('Y-m-d'))
                ->orderBy('event_date', 'asc')
                ->limit($limit)
                ->get()
                ->map(function ($event) {
                    return $this->transformEvent($event);
                });

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming events',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified race event for API consumption.
     */
    public function show($id): JsonResponse
    {
        try {
            $event = RaceEvent::with('creator')
                ->find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Race event not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->transformEvent($event)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch race event',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get race events by date range for API consumption.
     */
    public function byDateRange(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Start date and end date are required'
                ], 422);
            }

            $events = RaceEvent::with('creator')
                ->where('is_active', true)
                ->whereBetween('event_date', [$startDate, $endDate])
                ->orderBy('event_date', 'asc')
                ->get()
                ->map(function ($event) {
                    return $this->transformEvent($event);
                });

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events by date range',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created race event via API.
     */
    public function store(Request $request): JsonResponse
    {
        try {
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
            $validated['is_active'] = $request->has('is_active') ? true : true;

            $event = RaceEvent::create($validated);
            $event->load('creator');

            return response()->json([
                'success' => true,
                'message' => 'Race event created successfully',
                'data' => $this->transformEvent($event)
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create race event',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified race event via API.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $event = RaceEvent::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Race event not found'
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'event_date' => 'sometimes|required|date',
                'location' => 'sometimes|required|string|max:255',
                'start_time' => 'sometimes|required|date_format:H:i',
                'expected_runners' => 'nullable|string|max:255',
                'distance_options' => 'sometimes|required|string',
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
            if ($request->has('is_featured')) {
                $validated['is_featured'] = $request->boolean('is_featured');
            }
            if ($request->has('is_active')) {
                $validated['is_active'] = $request->boolean('is_active');
            }

            $event->update($validated);
            $event->load('creator');

            return response()->json([
                'success' => true,
                'message' => 'Race event updated successfully',
                'data' => $this->transformEvent($event)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update race event',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified race event via API.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $event = RaceEvent::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Race event not found'
                ], 404);
            }

            // Delete associated image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Race event deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete race event',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Transform event data to include full image URL and formatted dates.
     */
    private function transformEvent(RaceEvent $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'event_date' => $event->event_date,
            'event_date_formatted' => $event->event_date->format('F j, Y'),
            'location' => $event->location,
            'start_time' => $event->start_time,
            'start_time_formatted' => $event->start_time ? date('g:i A', strtotime($event->start_time)) : null,
            'expected_runners' => $event->expected_runners,
            'distance_options' => $event->distance_options,
            'image' => $event->image ? Storage::disk('public')->url($event->image) : null,
            'image_thumbnail' => $event->image ? Storage::disk('public')->url($event->image) : null, // You can add thumbnail logic here
            'description' => $event->description,
            'registration_link' => $event->registration_link,
            'is_featured' => $event->is_featured,
            'is_active' => $event->is_active,
            'created_by' => $event->created_by,
            'creator' => $event->creator ? [
                'id' => $event->creator->id,
                'name' => $event->creator->name,
                'email' => $event->creator->email,
            ] : null,
            'created_at' => $event->created_at,
            'created_at_formatted' => $event->created_at->format('F j, Y g:i A'),
            'updated_at' => $event->updated_at,
            'updated_at_formatted' => $event->updated_at->format('F j, Y g:i A'),
            'is_upcoming' => $event->event_date >= now()->format('Y-m-d'),
            'days_until_event' => now()->diffInDays($event->event_date, false),
        ];
    }
}
