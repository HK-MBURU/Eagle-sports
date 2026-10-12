<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    /**
     * Get all published stories with filters
     * GET /api/stories
     */

    private function getClientBaseUrl(): string
    {
        return config('app.frontend_url', config('app.url'));
    }

    private function getStoryClientUrl(string $slug): string
    {
        $clientBaseUrl = rtrim($this->getClientBaseUrl(), '/');
        return "{$clientBaseUrl}/stories/{$slug}";
    }


    public function index(Request $request): JsonResponse
    {
        $query = Story::published()->latest();

        // Filter by category
        if ($request->has('category') && $request->category !== 'All') {
            $query->category($request->category);
        }

        // Filter by featured
        if ($request->has('featured') && $request->featured == 1) {
            $query->featured();
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%')
                    ->orWhere('author_name', 'like', '%' . $request->search . '%')
                    ->orWhere('meta_keywords', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 9);
        $stories = $query->paginate($perPage);

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'is_featured' => $story->is_featured,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'published_date' => $story->published_date->format('Y-m-d'),
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    'meta_title' => $story->meta_title,
                    'meta_description' => $story->meta_description,
                    'meta_keywords' => $story->meta_keywords,
                    'og_image_url' => $story->og_image_url,
                    'youtube_url' => $story->youtube_url,
                    'youtube_embed_url' => $story->youtube_embed_url,
                    'youtube_video_id' => $story->youtube_video_id,
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                    'formatted_hashtags' => $story->formatted_hashtags,
                ];
            }),
            'meta' => [
                'current_page' => $stories->currentPage(),
                'last_page' => $stories->lastPage(),
                'per_page' => $stories->perPage(),
                'total' => $stories->total(),
                'from' => $stories->firstItem(),
                'to' => $stories->lastItem(),
            ]
        ]);
    }

    /**
     * Get featured stories
     * GET /api/stories/featured
     */
    public function featured(): JsonResponse
    {
        $stories = Story::published()
            ->featured()
            ->latest()
            ->take(3)
            ->get();

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    'youtube_embed_url' => $story->youtube_embed_url,
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                    'formatted_hashtags' => $story->formatted_hashtags,
                ];
            })
        ]);
    }

    /**
     * Get single story by slug or ID
     * GET /api/stories/{slug}
     */
    public function show(string $identifier): JsonResponse
    {
        $story = Story::published()
            ->where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (!$story) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found'
            ], 404);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $story->id,
                'title' => $story->title,
                'slug' => $story->slug,
                'excerpt' => $story->excerpt,
                'content' => $story->content,
                'image_url' => $story->image_url,
                'image_attribution' => $story->image_attribution,
                'category' => $story->category,
                'is_featured' => $story->is_featured,
                'author_name' => $story->author_name,
                'read_time' => $story->read_time,
                'published_date' => $story->published_date->format('Y-m-d'),
                'formatted_date' => $story->formatted_date,
                'likes_count' => $story->likes()->count(),
                'is_liked' => $user ? $story->isLikedBy($user) : false,
                // SEO fields for frontend rendering
                'meta_title' => $story->meta_title ?? $story->title,
                'meta_description' => $story->meta_description ?? strip_tags($story->excerpt),
                'meta_keywords' => $story->meta_keywords,
                'og_image_url' => $story->og_image_url,
                // Media field
                'youtube_url' => $story->youtube_url,
                'youtube_embed_url' => $story->youtube_embed_url,
                'youtube_video_id' => $story->youtube_video_id,
                // Social media hashtags
                'hashtags' => $story->hashtags,
                'hashtags_array' => $story->hashtags_array,
                'formatted_hashtags' => $story->formatted_hashtags,
                // Additional timestamps
                'created_at' => $story->created_at->toIso8601String(),
                'updated_at' => $story->updated_at->toIso8601String(),
                'meta_tags' => [
                    'title' => $story->meta_title ?? $story->title,
                    'description' => $story->meta_description ?? strip_tags($story->excerpt),
                    'image' => $story->og_image_url ?? $story->image_url,
                    'url' => $this->getStoryClientUrl($story->slug),
                    'type' => 'article',
                    'published_time' => $story->published_date->toIso8601String(),
                    'modified_time' => $story->updated_at->toIso8601String(),
                    'author' => $story->author_name,
                    'section' => $story->category,
                ]
            ]
        ]);
    }

    /**
     * Get stories by category
     * GET /api/stories/category/{category}
     */
    public function byCategory(string $category): JsonResponse
    {
        $stories = Story::published()
            ->category($category)
            ->latest()
            ->paginate(9);

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    'youtube_embed_url' => $story->youtube_embed_url,
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                ];
            }),
            'meta' => [
                'current_page' => $stories->currentPage(),
                'last_page' => $stories->lastPage(),
                'per_page' => $stories->perPage(),
                'total' => $stories->total(),
                'from' => $stories->firstItem(),
                'to' => $stories->lastItem(),
            ]
        ]);
    }

    /**
     * Get available categories with story counts
     * GET /api/stories/categories
     */
    public function categories(): JsonResponse
    {
        $categoriesWithCounts = Story::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        $allCategories = collect(Story::CATEGORIES)->map(function ($category) use ($categoriesWithCounts) {
            return [
                'name' => $category,
                'count' => $categoriesWithCounts[$category] ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $allCategories
        ]);
    }

    /**
     * Get related stories (same category, different story)
     * GET /api/stories/{id}/related
     */
    public function related(Story $story): JsonResponse
    {
        if (!$story->isPublished()) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found'
            ], 404);
        }

        $user = Auth::user();

        $relatedStories = Story::published()
            ->where('id', '!=', $story->id)
            ->where('category', $story->category)
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $relatedStories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                ];
            })
        ]);
    }

    /**
     * Get latest stories
     * GET /api/stories/latest
     */
    public function latest(): JsonResponse
    {
        $stories = Story::published()
            ->latest()
            ->take(6)
            ->get();

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    'is_featured' => $story->is_featured,
                ];
            })
        ]);
    }

    /**
     * Get SEO meta data for a story
     * GET /api/stories/{slug}/seo
     */
    public function seoMeta(string $identifier): JsonResponse
    {
        $story = Story::published()
            ->where('slug', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        if (!$story) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'story_id' => $story->id,
                'story_slug' => $story->slug,
                'meta' => [
                    'title' => $story->meta_title ?? $story->title,
                    'description' => $story->meta_description ?? strip_tags($story->excerpt),
                    'keywords' => $story->meta_keywords,
                    'og_title' => $story->meta_title ?? $story->title,
                    'og_description' => $story->meta_description ?? strip_tags($story->excerpt),
                    'og_image' => $story->og_image_url,
                    'og_type' => 'article',
                    'article_published_time' => $story->published_date->toIso8601String(),
                    'article_modified_time' => $story->updated_at->toIso8601String(),
                    'article_author' => $story->author_name,
                    'article_section' => $story->category,
                    'canonical_url' => url("/stories/{$story->slug}"),
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                    'formatted_hashtags' => $story->formatted_hashtags,
                ]
            ]
        ]);
    }

    /**
     * Get story SEO data for meta tags
     * GET /api/stories/{slug}/seo
     */
    public function seo(string $slug): JsonResponse
    {
        $story = Story::published()
            ->where('slug', $slug)
            ->first();

        if (!$story) {
            return response()->json([
                'success' => false,
                'message' => 'Story not found'
            ], 404);
        }



        return response()->json([
            'success' => true,
            'data' => [
                'meta_title' => $story->meta_title ?? $story->title,
                'meta_description' => $story->meta_description ?? strip_tags($story->excerpt),
                'meta_keywords' => $story->meta_keywords,
                'og_title' => $story->meta_title ?? $story->title,
                'og_description' => $story->meta_description ?? strip_tags($story->excerpt),
                'og_image' => $story->og_image_url,
                'og_type' => 'article',
                'article_published_time' => $story->published_date->toIso8601String(),
                'article_modified_time' => $story->updated_at->toIso8601String(),
                'article_author' => $story->author_name,
                'article_section' => $story->category,
                'canonical_url' => url("/stories/{$story->slug}"),
            ]
        ]);
    }

    /**
     * Search stories with advanced filters
     * POST /api/stories/search
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'author' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'featured_only' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $query = Story::published();

        // Text search
        if ($request->filled('query')) {
            $searchTerm = $request->query;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%")
                    ->orWhere('meta_keywords', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Author filter
        if ($request->filled('author')) {
            $query->where('author_name', 'like', "%{$request->author}%");
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('published_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('published_date', '<=', $request->date_to);
        }

        // Featured only
        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        $perPage = $request->get('per_page', 12);
        $stories = $query->latest()->paginate($perPage);

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'query' => $request->query,
            'filters' => [
                'category' => $request->category,
                'author' => $request->author,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'featured_only' => $request->boolean('featured_only'),
            ],
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'is_featured' => $story->is_featured,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                ];
            }),
            'meta' => [
                'current_page' => $stories->currentPage(),
                'last_page' => $stories->lastPage(),
                'per_page' => $stories->perPage(),
                'total' => $stories->total(),
                'from' => $stories->firstItem(),
                'to' => $stories->lastItem(),
            ]
        ]);
    }

    /**
     * Get popular stories (most liked)
     * GET /api/stories/popular
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 5);

        $stories = Story::published()
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->take($limit)
            ->get();

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes_count,
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                ];
            })
        ]);
    }

    /**
     * Get stories by author
     * GET /api/stories/author/{author}
     */
    public function byAuthor(string $author): JsonResponse
    {
        $stories = Story::published()
            ->where('author_name', 'like', "%{$author}%")
            ->latest()
            ->paginate(9);

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'author' => $author,
            'data' => $stories->map(function ($story) use ($user) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'image_attribution' => $story->image_attribution,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                    'likes_count' => $story->likes()->count(),
                    'is_liked' => $user ? $story->isLikedBy($user) : false,
                    // Social media hashtags
                    'hashtags' => $story->hashtags,
                    'hashtags_array' => $story->hashtags_array,
                ];
            }),
            'meta' => [
                'current_page' => $stories->currentPage(),
                'last_page' => $stories->lastPage(),
                'total' => $stories->total(),
            ]
        ]);
    }

    /**
     * Get story statistics
     * GET /api/stories/stats
     */
    public function stats(): JsonResponse
    {
        $totalStories = Story::published()->count();
        $totalLikes = Story::published()->withCount('likes')->get()->sum('likes_count');
        $categoryCounts = Story::published()
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        return response()->json([
            'success' => true,
            'data' => [
                'total_stories' => $totalStories,
                'total_likes' => $totalLikes,
                'featured_stories' => Story::published()->featured()->count(),
                'stories_with_video' => Story::published()->whereNotNull('youtube_url')->count(),
                'categories' => $categoryCounts,
                'latest_story_date' => Story::published()->latest('published_date')->first()?->published_date->format('Y-m-d'),
            ]
        ]);
    }


    public function homepageFeatured(): JsonResponse
    {
        $user = Auth::user();

        // Get the latest featured story
        $featuredStory = Story::published()
            ->featured()
            ->latest()
            ->first();

        // Get 3 latest regular stories (excluding the featured one if exists)
        $regularStoriesQuery = Story::published()
            ->latest();

        if ($featuredStory) {
            $regularStoriesQuery->where('id', '!=', $featuredStory->id);
        }

        $regularStories = $regularStoriesQuery->take(3)->get();

        // Format featured story (only if exists)
        $formattedFeatured = $featuredStory ? [
            'id' => $featuredStory->id,
            'title' => $featuredStory->title,
            'slug' => $featuredStory->slug,
            'excerpt' => $featuredStory->excerpt,
            'image_url' => $featuredStory->image_url,
            'image_attribution' => $featuredStory->image_attribution,
            'category' => $featuredStory->category,
            'author_name' => $featuredStory->author_name,
            'read_time' => $featuredStory->read_time,
            'formatted_date' => $featuredStory->formatted_date,
            'likes_count' => $featuredStory->likes()->count(),
            'is_liked' => $user ? $featuredStory->isLikedBy($user) : false,
            'youtube_embed_url' => $featuredStory->youtube_embed_url,
        ] : null;

        // Format regular stories
        $formattedRegular = $regularStories->map(function ($story) use ($user) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'slug' => $story->slug,
                'excerpt' => $story->excerpt,
                'image_url' => $story->image_url,
                'image_attribution' => $story->image_attribution,
                'category' => $story->category,
                'author_name' => $story->author_name,
                'read_time' => $story->read_time,
                'formatted_date' => $story->formatted_date,
                'likes_count' => $story->likes()->count(),
                'is_liked' => $user ? $story->isLikedBy($user) : false,
                'youtube_embed_url' => $story->youtube_embed_url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'featured' => $formattedFeatured,
                'regular' => $formattedRegular,
                'total_stories' => ($featuredStory ? 1 : 0) + $regularStories->count(),
            ]
        ]);
    }

    public function shareData(string $slug): JsonResponse
    {
        $story = Story::published()->where('slug', $slug)->firstOrFail();

        // Use the API meta preview URL so every crawler gets static OG/Twitter tags.
        $shareUrl = route('api.stories.meta', ['slug' => $story->slug], true);
        $shareTitle = $story->meta_title ?? $story->title;
        $shareDescription = $story->meta_description ?? strip_tags($story->excerpt);

        $hashtags = collect(explode(',', (string) $story->hashtags))
            ->map(fn($tag) => trim(ltrim($tag, '#')))
            ->filter()
            ->implode(',');

        return response()->json([
            'success' => true,
            'data' => [
                'share_url' => $shareUrl,
                'title' => $shareTitle,
                'description' => $shareDescription,
                'image' => $story->og_image_url ?? $story->image_url,
                'hashtags' => $story->hashtags,
                'hashtags_array' => $story->hashtags_array,
                'social_links' => [
                    'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($shareUrl),
                    'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($shareUrl) . "&text=" . urlencode($shareTitle) . ($hashtags ? "&hashtags={$hashtags}" : ''),
                    'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($shareUrl),
                    'whatsapp' => "https://wa.me/?text=" . urlencode($shareTitle . "\n\n" . $shareDescription . "\n\n" . $shareUrl),
                    'email' => "mailto:?subject=" . urlencode($shareTitle) . "&body=" . urlencode($shareDescription . "\n\n" . $shareUrl),
                ]
            ]
        ]);
    }
}
