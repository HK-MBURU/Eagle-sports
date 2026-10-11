<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\Like;
use App\Models\StoryLike;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StoryLikeController extends Controller
{
    /**
     * Toggle like on a story
     * POST /api/stories/{story}/like
     */
    public function toggle(Story $story): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like stories'
            ], 401);
        }

        // Check if already liked
        $existingLike = StoryLike::where('user_id', $user->id)
            ->where('story_id', $story->id)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $isLiked = false;
            $message = 'Story unliked successfully';
        } else {
            // Like
            StoryLike::create([
                'user_id' => $user->id,
                'story_id' => $story->id,
            ]);
            $isLiked = true;
            $message = 'Story liked successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $story->likes()->count(),
            ]
        ]);
    }

    /**
     * Get user's liked stories
     * GET /api/user/liked-stories
     */
    public function userLikes(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $likedStories = $user->likedStories()
            ->where('status', 'published')
            ->latest()
            ->get()
            ->map(function ($story) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'excerpt' => $story->excerpt,
                    'image_url' => $story->image_url,
                    'category' => $story->category,
                    'author_name' => $story->author_name,
                    'read_time' => $story->read_time,
                    'formatted_date' => $story->formatted_date,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $likedStories
        ]);
    }

    /**
     * Check if user has liked a story
     * GET /api/stories/{story}/is-liked
     */
    public function checkLiked(Story $story): JsonResponse
    {
        // Use Sanctum guard to get authenticated user (if any)
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_liked' => false,
                    'likes_count' => $story->likes()->count(),
                ]
            ]);
        }

        $isLiked = $story->isLikedBy($user);

        return response()->json([
            'success' => true,
            'data' => [
                'is_liked' => $isLiked,
                'likes_count' => $story->likes()->count(),
            ]
        ]);
    }
}
