<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WomenRunning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WomenRunningApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/women-running
     */
    public function index(Request $request)
    {
        try {
            $query = WomenRunning::query();

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by featured
            if ($request->has('featured')) {
                $query->where('featured', $request->featured);
            }

            // Search by name or title
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sort
            $sortBy = $request->get('sort_by', 'order');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginate
            $perPage = $request->get('per_page', 15);
            $entries = $query->paginate($perPage);

            // Transform data
            $entries->getCollection()->transform(function ($item) {
                return $this->transformEntry($item);
            });

            return response()->json([
                'success' => true,
                'message' => 'Entries retrieved successfully',
                'data' => $entries
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving entries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/women-running
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
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

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('women-running', 'public');
            }

            // Set defaults
            $data['featured'] = $request->boolean('featured', false);
            $data['status'] = $request->boolean('status', true);
            $data['order'] = $data['order'] ?? 0;

            $entry = WomenRunning::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Entry created successfully',
                'data' => $this->transformEntry($entry)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /api/women-running/{id}
     */
    public function show($id)
    {
        try {
            $entry = WomenRunning::find($id);

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Entry retrieved successfully',
                'data' => $this->transformEntry($entry)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT/PATCH /api/women-running/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $entry = WomenRunning::find($id);

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'category' => 'sometimes|required|in:mentorship,coaching,advocacy,featured_story',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'icon' => 'nullable|string|max:50',
                'featured' => 'nullable|boolean',
                'story_content' => 'nullable|string',
                'video_url' => 'nullable|url',
                'order' => 'nullable|integer|min:0',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($entry->image) {
                    Storage::disk('public')->delete($entry->image);
                }
                $data['image'] = $request->file('image')->store('women-running', 'public');
            }

            // Update boolean values if present
            if ($request->has('featured')) {
                $data['featured'] = $request->boolean('featured');
            }
            if ($request->has('status')) {
                $data['status'] = $request->boolean('status');
            }

            $entry->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Entry updated successfully',
                'data' => $this->transformEntry($entry->fresh())
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/women-running/{id}
     */
    public function destroy($id)
    {
        try {
            $entry = WomenRunning::find($id);

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found'
                ], 404);
            }

            // Delete image if exists
            if ($entry->image) {
                Storage::disk('public')->delete($entry->image);
            }

            $entry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Entry deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting entry',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle entry status.
     * PATCH /api/women-running/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        try {
            $entry = WomenRunning::find($id);

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found'
                ], 404);
            }

            $entry->update(['status' => !$entry->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status toggled successfully',
                'data' => $this->transformEntry($entry->fresh())
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle featured status.
     * PATCH /api/women-running/{id}/toggle-featured
     */
    public function toggleFeatured($id)
    {
        try {
            $entry = WomenRunning::find($id);

            if (!$entry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry not found'
                ], 404);
            }

            $entry->update(['featured' => !$entry->featured]);

            return response()->json([
                'success' => true,
                'message' => 'Featured status toggled successfully',
                'data' => $this->transformEntry($entry->fresh())
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling featured status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured entries only.
     * GET /api/women-running/featured
     */
    public function featured(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $entries = WomenRunning::where('featured', true)
                ->where('status', true)
                ->orderBy('order', 'asc')
                ->paginate($perPage);

            $entries->getCollection()->transform(function ($item) {
                return $this->transformEntry($item);
            });

            return response()->json([
                'success' => true,
                'message' => 'Featured entries retrieved successfully',
                'data' => $entries
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving featured entries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get entries by category.
     * GET /api/women-running/category/{category}
     */
    public function byCategory($category, Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);

            $entries = WomenRunning::where('category', $category)
                ->where('status', true)
                ->orderBy('order', 'asc')
                ->paginate($perPage);

            if ($entries->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No entries found for this category',
                    'data' => $entries
                ], 200);
            }

            $entries->getCollection()->transform(function ($item) {
                return $this->transformEntry($item);
            });

            return response()->json([
                'success' => true,
                'message' => 'Entries retrieved successfully',
                'data' => $entries
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving entries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics.
     * GET /api/women-running/statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_entries' => WomenRunning::count(),
                'active_entries' => WomenRunning::where('status', true)->count(),
                'featured_entries' => WomenRunning::where('featured', true)->count(),
                'by_category' => [
                    'mentorship' => WomenRunning::where('category', 'mentorship')->count(),
                    'coaching' => WomenRunning::where('category', 'coaching')->count(),
                    'advocacy' => WomenRunning::where('category', 'advocacy')->count(),
                    'featured_story' => WomenRunning::where('category', 'featured_story')->count(),
                ],
                'recent_entries' => WomenRunning::latest()->take(5)->get()->map(function($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'title' => $item->title,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transform entry data for API response.
     */
    private function transformEntry($entry)
    {
        return [
            'id' => $entry->id,
            'name' => $entry->name,
            'title' => $entry->title,
            'description' => $entry->description,
            'category' => $entry->category,
            'category_label' => ucfirst(str_replace('_', ' ', $entry->category)),
            'icon' => $entry->icon,
            'image' => $entry->image ? asset('storage/' . $entry->image) : null,
            'image_path' => $entry->image,
            'featured' => (bool) $entry->featured,
            'status' => (bool) $entry->status,
            'story_content' => $entry->story_content,
            'video_url' => $entry->video_url,
            'order' => $entry->order,
            'created_at' => $entry->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $entry->updated_at->format('Y-m-d H:i:s'),
            'created_at_human' => $entry->created_at->diffForHumans(),
            'updated_at_human' => $entry->updated_at->diffForHumans(),
        ];
    }
}
