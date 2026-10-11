<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RaceResultsApiController extends Controller
{
    /**
     * Get all race results with pagination
     * GET /api/race-results
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Result::with('uploader:id,name')
                ->published();

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Filter by featured
            if ($request->filled('featured')) {
                $query->where('is_featured', true);
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->where('race_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('race_date', '<=', $request->date_to);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $results = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $results->items(),
                'meta' => [
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                    'from' => $results->firstItem(),
                    'to' => $results->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch race results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single race result
     * GET /api/race-results/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            $result = Result::with('uploader:id,name')
                ->published()
                ->findOrFail($id);

            // Increment views
            $result->incrementViews();

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Race result not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get featured race results
     * GET /api/race-results/featured
     */
    public function featured(): JsonResponse
    {
        try {
            $results = Result::with('uploader:id,name')
                ->published()
                ->featured()
                ->latest()
                ->limit(6)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest race results
     * GET /api/race-results/latest
     */
    public function latest(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);

            $results = Result::with('uploader:id,name')
                ->published()
                ->latest()
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch latest results',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get race results by category
     * GET /api/race-results/category/{category}
     */
    public function byCategory($category): JsonResponse
    {
        try {
            $results = Result::with('uploader:id,name')
                ->published()
                ->byCategory($category)
                ->latest()
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $results->items(),
                'meta' => [
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'per_page' => $results->perPage(),
                    'total' => $results->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch results by category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available categories with counts
     * GET /api/race-results/categories
     */
    public function categories(): JsonResponse
    {
        try {
            $categories = Result::published()
                ->selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download race result file
     * GET /api/race-results/{id}/download
     */
    public function download($id): JsonResponse
    {
        try {
            $result = Result::published()->findOrFail($id);

            // Increment downloads
            $result->incrementDownloads();

            return response()->json([
                'success' => true,
                'data' => [
                    'download_url' => $result->full_file_url,
                    'file_name' => $result->file_name,
                    'file_size' => $result->formatted_file_size,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get download link',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get race results statistics
     * GET /api/race-results/stats
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total' => Result::published()->count(),
                'featured' => Result::published()->featured()->count(),
                'total_views' => Result::published()->sum('views_count'),
                'total_downloads' => Result::published()->sum('downloads_count'),
                'by_category' => Result::published()
                    ->selectRaw('category, count(*) as count')
                    ->groupBy('category')
                    ->orderBy('count', 'desc')
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
