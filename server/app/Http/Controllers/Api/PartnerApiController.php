<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerApiController extends Controller
{
    /**
     * Display a listing of the partners for API consumption.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $featured = $request->get('featured');
            $search = $request->get('search');

            $query = Partner::with('addedBy')
                ->select([
                    'id',
                    'name',
                    'logo',
                    'is_featured',
                    'added_by',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by featured partners
            if ($featured !== null) {
                $query->where('is_featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
            }

            // Search functionality
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            // Order by featured first, then by name
            $query->orderBy('is_featured', 'desc')
                  ->orderBy('name', 'asc');

            $partners = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform the data to include full logo URLs
            $partners->getCollection()->transform(function ($partner) {
                return $this->transformPartner($partner);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'partners' => $partners->items(),
                    'pagination' => [
                        'current_page' => $partners->currentPage(),
                        'last_page' => $partners->lastPage(),
                        'per_page' => $partners->perPage(),
                        'total' => $partners->total(),
                        'from' => $partners->firstItem(),
                        'to' => $partners->lastItem(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch partners',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display featured partners for API consumption.
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 12);

            $partners = Partner::with('addedBy')
                ->where('is_featured', true)
                ->orderBy('name', 'asc')
                ->limit($limit)
                ->get()
                ->map(function ($partner) {
                    return $this->transformPartner($partner);
                });

            return response()->json([
                'success' => true,
                'data' => $partners
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured partners',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display all partners in alphabetical order for API consumption.
     */
    public function alphabetical(): JsonResponse
    {
        try {
            $partners = Partner::with('addedBy')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function ($partner) {
                    return $this->transformPartner($partner);
                });

            return response()->json([
                'success' => true,
                'data' => $partners
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch partners',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified partner for API consumption.
     */
    public function show($id): JsonResponse
    {
        try {
            $partner = Partner::with('addedBy')->find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partner not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->transformPartner($partner)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch partner',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created partner via API.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_featured' => 'boolean',
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('partners', 'public');
            }

            // Set the user who added the partner
            $validated['added_by'] = auth()->id();

            // Set default value for is_featured checkbox
            $validated['is_featured'] = $request->has('is_featured');

            $partner = Partner::create($validated);
            $partner->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Partner added successfully',
                'data' => $this->transformPartner($partner)
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
                'message' => 'Failed to create partner',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified partner via API.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $partner = Partner::find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partner not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'is_featured' => 'boolean',
            ]);

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($partner->logo) {
                    Storage::disk('public')->delete($partner->logo);
                }
                $validated['logo'] = $request->file('logo')->store('partners', 'public');
            }

            // Set checkbox value
            if ($request->has('is_featured')) {
                $validated['is_featured'] = $request->boolean('is_featured');
            }

            $partner->update($validated);
            $partner->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Partner updated successfully',
                'data' => $this->transformPartner($partner)
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
                'message' => 'Failed to update partner',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified partner via API.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $partner = Partner::find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partner not found'
                ], 404);
            }

            // Delete associated logo if exists
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }

            $partner->delete();

            return response()->json([
                'success' => true,
                'message' => 'Partner deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete partner',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Toggle featured status for a partner via API.
     */
    public function toggleFeatured($id): JsonResponse
    {
        try {
            $partner = Partner::find($id);

            if (!$partner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partner not found'
                ], 404);
            }

            $partner->update([
                'is_featured' => !$partner->is_featured
            ]);

            $status = $partner->is_featured ? 'featured' : 'unfeatured';

            return response()->json([
                'success' => true,
                'message' => "Partner {$status} successfully",
                'data' => $this->transformPartner($partner)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle partner featured status',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search partners via API.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'search' => 'required|string|min:2'
            ]);

            $searchTerm = $request->input('search');
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            $partners = Partner::with('addedBy')
                ->where('name', 'like', "%{$searchTerm}%")
                ->orderBy('is_featured', 'desc')
                ->orderBy('name', 'asc')
                ->paginate($perPage, ['*'], 'page', $page);

            $partners->getCollection()->transform(function ($partner) {
                return $this->transformPartner($partner);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'partners' => $partners->items(),
                    'search_term' => $searchTerm,
                    'pagination' => [
                        'current_page' => $partners->currentPage(),
                        'last_page' => $partners->lastPage(),
                        'per_page' => $partners->perPage(),
                        'total' => $partners->total(),
                        'from' => $partners->firstItem(),
                        'to' => $partners->lastItem(),
                    ]
                ]
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
                'message' => 'Failed to search partners',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Transform partner data to include full logo URL and formatted data.
     */
    private function transformPartner(Partner $partner): array
    {
        return [
            'id' => $partner->id,
            'name' => $partner->name,
            'logo' => $partner->logo ? Storage::disk('public')->url($partner->logo) : null,
            'logo_thumbnail' => $partner->logo ? Storage::disk('public')->url($partner->logo) : null,
            'is_featured' => $partner->is_featured,
            'added_by' => $partner->added_by,
            'creator' => $partner->addedBy ? [
                'id' => $partner->addedBy->id,
                'name' => $partner->addedBy->name,
                'email' => $partner->addedBy->email,
            ] : null,
            'created_at' => $partner->created_at,
            'created_at_formatted' => $partner->created_at->format('F j, Y g:i A'),
            'updated_at' => $partner->updated_at,
            'updated_at_formatted' => $partner->updated_at->format('F j, Y g:i A'),
        ];
    }
}
