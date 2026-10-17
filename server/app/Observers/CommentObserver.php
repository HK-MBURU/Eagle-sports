<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\AdminNotification;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        $story = $comment->story;
        $storyTitle = $story?->title ?? 'a story';

        AdminNotification::notify(
            type: 'new_comment',
            title: 'New comment posted',
            message: ($comment->user?->name ?? 'A visitor') . " commented on \"{$storyTitle}\"",
            data: [
                'comment_id' => $comment->id,
                'story_id'   => $comment->story_id,
                'user_name'  => $comment->user?->name,
            ],
            link: "/admin/stories/{$comment->story_id}/edit",
        );
    }
}
