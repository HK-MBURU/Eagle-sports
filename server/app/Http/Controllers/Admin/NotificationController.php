<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function poll(): JsonResponse
    {
        $this->checkStaleSections();

        $unreadCount = AdminNotification::unread()->count();
        $recent = AdminNotification::latestFirst()
            ->limit(8)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'type'    => $n->type,
                'title'   => $n->title,
                'message' => $n->message,
                'link'    => $n->link,
                'icon'    => $n->icon,
                'color'   => $n->color,
                'bg'      => $n->bg,
                'unread'  => $n->isUnread(),
                'time'    => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $recent,
        ]);
    }

    public function markRead(int $id): JsonResponse
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markRead();
        return response()->json(['ok' => true]);
    }

    public function markAllRead(): JsonResponse
    {
        AdminNotification::unread()->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }

    public function destroy(int $id): JsonResponse
    {
        AdminNotification::findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    private function checkStaleSections(): void
    {
        foreach (AdminNotification::STALE_THRESHOLDS as $type => $config) {
            $modelClass = $config['model'];
            $days       = $config['days'];
            $label      = $config['label'];
            $link       = $config['link'];

            $lastRecord = $modelClass::latest()->first();
            $isStale = !$lastRecord || !$lastRecord->created_at || $lastRecord->created_at->diffInDays(now()) >= $days;

            if ($isStale) {
                // Create only if no unread stale notification of this type exists
                if (!AdminNotification::where('type', $type)->whereNull('read_at')->exists()) {
                    $daysSince = ($lastRecord && $lastRecord->created_at)
                        ? (int) $lastRecord->created_at->diffInDays(now())
                        : null;

                    $message = $daysSince !== null
                        ? "No new {$label} content in {$daysSince} days — consider adding more."
                        : "No content has been added to {$label} yet.";

                    AdminNotification::notify($type, "{$label} needs attention", $message, [], $link);
                }
            } else {
                // Section is fresh — dismiss any unread stale notification for it
                AdminNotification::where('type', $type)->whereNull('read_at')->update(['read_at' => now()]);
            }
        }
    }
}
