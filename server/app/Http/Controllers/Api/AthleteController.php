<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AthleteController extends Controller
{
    /**
     * Get all active athletes
     * GET /api/athletes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Athlete::active()->ordered();

        // Filter by featured
        if ($request->has('featured') && $request->featured == 1) {
            $query->featured();
        }

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $athletes = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $athletes->map(function($athlete) {
                return [
                    'id' => $athlete->id,
                    'name' => $athlete->name,
                    'title' => $athlete->title,
                    'description' => $athlete->description,
                    'bio' => $athlete->bio,
                    'image_url' => $athlete->image_url,
                    'badge' => $athlete->badge,
                    'badge_color' => $athlete->badge_color,
                    'is_featured' => $athlete->is_featured,
                    'display_order' => $athlete->display_order,
                ];
            }),
            'meta' => [
                'current_page' => $athletes->currentPage(),
                'last_page' => $athletes->lastPage(),
                'per_page' => $athletes->perPage(),
                'total' => $athletes->total(),
            ]
        ]);
    }

    /**
     * Get featured athletes
     * GET /api/athletes/featured
     */
    public function featured(): JsonResponse
    {
        $athletes = Athlete::active()
            ->featured()
            ->ordered()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $athletes->map(function($athlete) {
                return [
                    'id' => $athlete->id,
                    'name' => $athlete->name,
                    'title' => $athlete->title,
                    'description' => $athlete->description,
                    'image_url' => $athlete->image_url,
                    'badge' => $athlete->badge,
                    'badge_color' => $athlete->badge_color,
                    'is_featured' => $athlete->is_featured,
                ];
            })
        ]);
    }

    /**
     * Get single athlete by ID
     * GET /api/athletes/{id}
     */
    public function show(int $id): JsonResponse
    {
        $athlete = Athlete::active()->find($id);

        if (!$athlete) {
            return response()->json([
                'success' => false,
                'message' => 'Athlete not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $athlete->id,
                'name' => $athlete->name,
                'title' => $athlete->title,
                'description' => $athlete->description,
                'bio' => $athlete->bio,
                'image_url' => $athlete->image_url,
                'badge' => $athlete->badge,
                'badge_color' => $athlete->badge_color,
                'is_featured' => $athlete->is_featured,
                'display_order' => $athlete->display_order,
            ]
        ]);
    }

    /**
     * Get latest athletes
     * GET /api/athletes/latest
     */
    public function latest(): JsonResponse
    {
        $athletes = Athlete::active()
            ->latest()
            ->take(8)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $athletes->map(function($athlete) {
                return [
                    'id' => $athlete->id,
                    'name' => $athlete->name,
                    'title' => $athlete->title,
                    'description' => $athlete->description,
                    'image_url' => $athlete->image_url,
                    'badge' => $athlete->badge,
                    'badge_color' => $athlete->badge_color,
                    'is_featured' => $athlete->is_featured,
                ];
            })
        ]);
    }

    /**
     * Get athlete statistics
     * GET /api/athletes/stats
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total' => Athlete::count(),
                'active' => Athlete::active()->count(),
                'featured' => Athlete::featured()->count(),
            ]
        ]);
    }
}