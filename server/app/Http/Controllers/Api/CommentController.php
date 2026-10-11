<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Story;
use App\Models\CommentLike;
use App\Models\CommentReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Get all comments for a story
     * GET /api/stories/{story}/comments
     */
    public function index(Story $story): JsonResponse
    {
        try {
            $user = Auth::user();

            // Get parent comments with replies
            $comments = Comment::where('story_id', $story->id)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user', 'replies.likes'])
                ->withCount('likes')
                ->latest()
                ->get();

            // Format comments for frontend
            $formattedComments = $comments->map(function ($comment) use ($user) {
                return $this->formatComment($comment, $user);
            });

            return response()->json([
                'success' => true,
                'data' => $formattedComments,
                'meta' => [
                    'total' => $comments->count(),
                    'story_id' => $story->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch comments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new comment
     * POST /api/stories/{story}/comments
     */
    public function store(Request $request, Story $story): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:1|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // If parent_id is provided, verify it belongs to the same story
            if ($request->parent_id) {
                $parentComment = Comment::find($request->parent_id);
                if ($parentComment->story_id !== $story->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid parent comment'
                    ], 400);
                }
            }

            $comment = Comment::create([
                'story_id' => $story->id,
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'content' => $request->content,
            ]);

            // Load relationships
            $comment->load('user', 'likes');

            return response()->json([
                'success' => true,
                'message' => $request->parent_id ? 'Reply posted successfully' : 'Comment posted successfully',
                'data' => $this->formatComment($comment, Auth::user())
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a comment
     * PATCH /api/comments/{comment}
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $comment->update([
                'content' => $request->content,
                'is_edited' => true,
                'edited_at' => now(),
            ]);

            $comment->load('user', 'likes');

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'data' => $this->formatComment($comment, Auth::user())
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a comment
     * DELETE /api/comments/{comment}
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Check if user owns the comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        try {
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle like on a comment
     * POST /api/comments/{comment}/like
     */
    public function toggleLike(Comment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        try {
            $userId = Auth::id();
            $like = CommentLike::where('comment_id', $comment->id)
                ->where('user_id', $userId)
                ->first();

            if ($like) {
                // Unlike
                $like->delete();
                $isLiked = false;
                $message = 'Comment unliked';
            } else {
                // Like
                CommentLike::create([
                    'comment_id' => $comment->id,
                    'user_id' => $userId,
                ]);
                $isLiked = true;
                $message = 'Comment liked';
            }

            $likesCount = $comment->likes()->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'is_liked' => $isLiked,
                    'likes_count' => $likesCount,
                    'comment_id' => $comment->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Report a comment
     * POST /api/comments/{comment}/report
     */
    public function report(Request $request, Comment $comment): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Check if user already reported this comment
        $existingReport = CommentReport::where('comment_id', $comment->id)
            ->where('reported_by', Auth::id())
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this comment'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|in:spam,harassment,inappropriate,misinformation,other',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            CommentReport::create([
                'comment_id' => $comment->id,
                'reported_by' => Auth::id(),
                'reason' => $request->reason,
                'description' => $request->description,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment reported successfully. Thank you for keeping our community safe.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to report comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get comment statistics for a story
     * GET /api/stories/{story}/comments/stats
     */
    public function stats(Story $story): JsonResponse
    {
        try {
            $totalComments = Comment::where('story_id', $story->id)->count();
            $parentComments = Comment::where('story_id', $story->id)
                ->whereNull('parent_id')
                ->count();
            $replies = Comment::where('story_id', $story->id)
                ->whereNotNull('parent_id')
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_comments' => $totalComments,
                    'parent_comments' => $parentComments,
                    'replies' => $replies,
                    'story_id' => $story->id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format comment data for frontend
     */
    private function formatComment(Comment $comment, $user = null): array
    {
        $formattedComment = [
            'id' => $comment->id,
            'author' => [
                'name' => $comment->user->name,
                'avatar' => $comment->user->avatar ?? null,
                'initials' => $this->getInitials($comment->user->name),
            ],
            'content' => $comment->content,
            'timestamp' => $comment->formatted_timestamp,
            'likes' => $comment->likes_count ?? $comment->likes()->count(),
            'is_liked' => $user ? $comment->isLikedBy($user) : false,
            'is_author' => $user ? $comment->user_id === $user->id : false,
            'is_edited' => $comment->is_edited,
            'edited_at' => $comment->edited_at ? $comment->edited_at->diffForHumans() : null,
            'created_at' => $comment->created_at->toIso8601String(),
        ];

        // Add replies if they exist
        if ($comment->relationLoaded('replies') && $comment->replies->count() > 0) {
            $formattedComment['replies'] = $comment->replies->map(function ($reply) use ($user) {
                return $this->formatComment($reply, $user);
            })->values()->toArray();
        }

        return $formattedComment;
    }

    /**
     * Get user initials from name
     */
    private function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return substr($initials, 0, 2); // Return first 2 initials
    }
}
