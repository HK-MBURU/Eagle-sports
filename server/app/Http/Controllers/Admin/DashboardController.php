<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Video;
use App\Models\WomenRunning;
use App\Models\Climate;
use App\Models\Race;
use App\Models\Story;
use App\Models\Quote;
use App\Models\Partner;
use App\Models\RaceEvent;
use App\Models\Result;
use App\Models\ResultEntry;
use App\Models\User;
use App\Models\Comment;
use App\Models\AdminNotification;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ── Core counts ──────────────────────────────────────────
        $storiesCount        = Story::count();
        $publishedStories    = Story::where('status', 'published')->count();
        $athletesCount       = Athlete::count();
        $eventsCount         = RaceEvent::count();
        $upcomingEvents      = RaceEvent::where('event_date', '>=', $now)->count();
        $videosCount         = Video::count();
        $womenRunningCount   = WomenRunning::count();
        $climateCount        = Climate::count();
        $partnersCount       = Partner::count();
        $quotesCount         = Quote::count();
        $resultDocsCount     = Result::count();
        $performanceCount    = ResultEntry::count();
        $usersCount          = User::count();
        $commentsCount       = Comment::count();

        // ── Section health: last content created ─────────────────
        $health = $this->sectionHealth();

        // ── Recent activity across all models ────────────────────
        $recentActivity = $this->recentActivity();

        // ── Unread notifications count ────────────────────────────
        $unreadNotifications = AdminNotification::unread()->count();

        return view('admin.dashboard', compact(
            'storiesCount', 'publishedStories',
            'athletesCount',
            'eventsCount', 'upcomingEvents',
            'videosCount',
            'womenRunningCount',
            'climateCount',
            'partnersCount',
            'quotesCount',
            'resultDocsCount',
            'performanceCount',
            'usersCount',
            'commentsCount',
            'health',
            'recentActivity',
            'unreadNotifications',
        ));
    }

    private function sectionHealth(): array
    {
        $sections = [
            ['key' => 'stories',      'label' => 'Stories',            'model' => Story::class,       'route' => 'admin.stories.index',        'icon' => 'newspaper',                'warn' => 7,  'alert' => 14],
            ['key' => 'events',       'label' => 'Race Events',         'model' => RaceEvent::class,   'route' => 'admin.events.index',          'icon' => 'calendar-event',           'warn' => 30, 'alert' => 60],
            ['key' => 'videos',       'label' => 'Videos',              'model' => Video::class,       'route' => 'admin.videos.index',          'icon' => 'play-circle',              'warn' => 30, 'alert' => 60],
            ['key' => 'women',        'label' => 'Women Running',       'model' => WomenRunning::class,'route' => 'admin.women-running.index',   'icon' => 'gender-female',            'warn' => 30, 'alert' => 60],
            ['key' => 'climate',      'label' => 'Climate',             'model' => Climate::class,     'route' => 'admin.climate.index',         'icon' => 'tree',                     'warn' => 30, 'alert' => 60],
            ['key' => 'performance',  'label' => 'Performance Entries', 'model' => ResultEntry::class, 'route' => 'admin.result-entries.index',  'icon' => 'stopwatch',                'warn' => 14, 'alert' => 30],
            ['key' => 'results',      'label' => 'Result Documents',    'model' => Result::class,      'route' => 'admin.results.index',         'icon' => 'file-earmark-arrow-down',  'warn' => 30, 'alert' => 60],
            ['key' => 'athletes',     'label' => 'Athletes',            'model' => Athlete::class,     'route' => 'admin.athletes.index',        'icon' => 'people',                   'warn' => 30, 'alert' => 90],
            ['key' => 'partners',     'label' => 'Partners',            'model' => Partner::class,     'route' => 'admin.partners.index',        'icon' => 'briefcase',                'warn' => 60, 'alert' => 120],
            ['key' => 'quotes',       'label' => 'Quotes',              'model' => Quote::class,       'route' => 'admin.quotes.index',          'icon' => 'chat-quote',               'warn' => 30, 'alert' => 60],
        ];

        return array_map(function ($s) {
            $last = $s['model']::latest()->first();
            $count = $s['model']::count();
            $daysSince = ($last && $last->created_at) ? (int) $last->created_at->diffInDays(now()) : null;

            $status = 'good';
            if ($daysSince === null || $daysSince >= $s['alert']) {
                $status = 'alert';
            } elseif ($daysSince >= $s['warn']) {
                $status = 'warn';
            }

            return array_merge($s, [
                'count'      => $count,
                'last_at'    => $last?->created_at,
                'days_since' => $daysSince,
                'status'     => $status,
            ]);
        }, $sections);
    }

    private function recentActivity(): array
    {
        $items = [];

        $push = function ($icon, $color, $message, $time, $link = null) use (&$items) {
            $items[] = compact('icon', 'color', 'message', 'time', 'link');
        };

        foreach (Story::latest()->limit(4)->get() as $s) {
            $push('newspaper', '#d62835', "Story published: \"{$s->title}\"", $s->created_at, route('admin.stories.edit', $s->id));
        }
        foreach (Comment::with('story')->latest()->limit(4)->get() as $c) {
            $push('chat-dots', '#2563eb', "Comment on \"{$c->story?->title}\"", $c->created_at);
        }
        foreach (User::latest()->limit(3)->get() as $u) {
            $push('person-plus', '#16a34a', "New user: {$u->name}", $u->created_at, route('admin.users.index'));
        }
        foreach (RaceEvent::latest()->limit(3)->get() as $e) {
            $push('calendar-event', '#7c3aed', "Event added: \"{$e->title}\"", $e->created_at, route('admin.events.edit', $e->id));
        }
        foreach (Video::latest()->limit(3)->get() as $v) {
            $push('play-circle', '#0891b2', "Video added: \"{$v->title}\"", $v->created_at, route('admin.videos.edit', $v->id));
        }
        foreach (ResultEntry::latest()->limit(3)->get() as $r) {
            $push('stopwatch', '#d97706', "{$r->athlete_name} - {$r->performance} ({$r->competition})", $r->created_at, route('admin.result-entries.edit', $r->id));
        }

        usort($items, fn($a, $b) => $b['time'] <=> $a['time']);
        return array_slice($items, 0, 15);
    }
}
