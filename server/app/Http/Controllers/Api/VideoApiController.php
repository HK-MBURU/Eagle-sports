<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoApiController extends Controller
{
    /**
     * Display a listing of the videos for API consumption.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $featured = $request->get('featured');
            $category = $request->get('category');
            $search = $request->get('search');

            $query = Video::with('addedBy')
                ->select([
                    'id',
                    'youtube_url',
                    'title',
                    'description',
                    'category',
                    'is_featured',
                    'added_by',
                    'created_at',
                    'updated_at'
                ]);

            // Filter by featured videos
            if ($featured !== null) {
                $query->where('is_featured', filter_var($featured, FILTER_VALIDATE_BOOLEAN));
            }

            // Filter by category
            if ($category && array_key_exists($category, Video::getCategories())) {
                $query->where('category', $category);
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Order by featured first, then by creation date
            $query->orderBy('is_featured', 'desc')
                  ->orderBy('created_at', 'desc');

            $videos = $query->paginate($perPage, ['*'], 'page', $page);

            // Transform the data to include YouTube embed URL and category name
            $videos->getCollection()->transform(function ($video) {
                return $this->transformVideo($video);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'videos' => $videos->items(),
                    'pagination' => [
                        'current_page' => $videos->currentPage(),
                        'last_page' => $videos->lastPage(),
                        'per_page' => $videos->perPage(),
                        'total' => $videos->total(),
                        'from' => $videos->firstItem(),
                        'to' => $videos->lastItem(),
                    ],
                    'categories' => Video::getCategories()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch videos',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display featured videos for API consumption.
     */
    public function featured(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);

            $videos = Video::with('addedBy')
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($video) {
                    return $this->transformVideo($video);
                });

            return response()->json([
                'success' => true,
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch featured videos',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display videos by category for API consumption.
     */
    public function byCategory($category): JsonResponse
    {
        try {
            if (!array_key_exists($category, Video::getCategories())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid category specified'
                ], 422);
            }

            $videos = Video::with('addedBy')
                ->where('category', $category)
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($video) {
                    return $this->transformVideo($video);
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'videos' => $videos,
                    'category' => $category,
                    'category_name' => Video::getCategories()[$category]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch videos by category',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified video for API consumption.
     */
    public function show($id): JsonResponse
    {
        try {
            $video = Video::with('addedBy')->find($id);

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->transformVideo($video)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch video',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get video categories for API consumption.
     */
    public function categories(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => Video::getCategories()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store a newly created video via API.
     */
    public function store(Request $request): JsonResponse
    {
        try {
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

            $video = Video::create($validated);
            $video->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Video added successfully',
                'data' => $this->transformVideo($video)
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
                'message' => 'Error adding video: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified video via API.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $video = Video::find($id);

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            $validated = $request->validate([
                'youtube_url' => 'sometimes|required|url',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'sometimes|required|in:' . implode(',', array_keys(Video::getCategories())),
                'is_featured' => 'boolean',
            ]);

            // Set checkbox value
            if ($request->has('is_featured')) {
                $validated['is_featured'] = $request->boolean('is_featured');
            }

            $video->update($validated);
            $video->load('addedBy');

            return response()->json([
                'success' => true,
                'message' => 'Video updated successfully',
                'data' => $this->transformVideo($video)
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
                'message' => 'Error updating video: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified video via API.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $video = Video::find($id);

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            $video->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting video: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Toggle featured status for a video via API.
     */
    public function toggleFeatured($id): JsonResponse
    {
        try {
            $video = Video::find($id);

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }

            $video->update([
                'is_featured' => !$video->is_featured
            ]);

            $status = $video->is_featured ? 'featured' : 'unfeatured';

            return response()->json([
                'success' => true,
                'message' => "Video {$status} successfully",
                'data' => $this->transformVideo($video)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating video status: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Search videos via API.
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

            $videos = Video::with('addedBy')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('title', 'like', "%{$searchTerm}%")
                          ->orWhere('description', 'like', "%{$searchTerm}%");
                })
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $videos->getCollection()->transform(function ($video) {
                return $this->transformVideo($video);
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'videos' => $videos->items(),
                    'search_term' => $searchTerm,
                    'pagination' => [
                        'current_page' => $videos->currentPage(),
                        'last_page' => $videos->lastPage(),
                        'per_page' => $videos->perPage(),
                        'total' => $videos->total(),
                        'from' => $videos->firstItem(),
                        'to' => $videos->lastItem(),
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
                'message' => 'Failed to search videos',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Transform video data to include YouTube embed URL and formatted data.
     */
    private function transformVideo(Video $video): array
    {
        return [
            'id' => $video->id,
            'youtube_url' => $video->youtube_url,
            'youtube_embed_url' => $this->getYouTubeEmbedUrl($video->youtube_url),
            'youtube_thumbnail' => $this->getYouTubeThumbnail($video->youtube_url),
            'title' => $video->title,
            'description' => $video->description,
            'category' => $video->category,
            'category_name' => Video::getCategories()[$video->category] ?? $video->category,
            'is_featured' => $video->is_featured,
            'added_by' => $video->added_by,
            'creator' => $video->addedBy ? [
                'id' => $video->addedBy->id,
                'name' => $video->addedBy->name,
                'email' => $video->addedBy->email,
            ] : null,
            'created_at' => $video->created_at,
            'created_at_formatted' => $video->created_at->format('F j, Y g:i A'),
            'updated_at' => $video->updated_at,
            'updated_at_formatted' => $video->updated_at->format('F j, Y g:i A'),
        ];
    }

    /**
     * Extract YouTube video ID from URL.
     */
    private function extractYouTubeId($url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get YouTube embed URL.
     */
    private function getYouTubeEmbedUrl($url): ?string
    {
        $videoId = $this->extractYouTubeId($url);
        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
    }

    /**
     * Get YouTube thumbnail URL.
     */
    private function getYouTubeThumbnail($url): ?string
    {
        $videoId = $this->extractYouTubeId($url);
        return $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : null;
    }
}
