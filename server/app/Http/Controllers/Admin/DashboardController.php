<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Article;
use App\Models\Video;
use App\Models\WomenRunning;
use App\Models\Climate;
use App\Models\Race;
use App\Models\Story;
use App\Models\Quote;
use App\Models\Partner;
use App\Models\RaceEvent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get counts for all content types
        $data = [
            'storiesCount' => Story::count(),
            'athletesCount' => Athlete::count(),
            'eventsCount' => RaceEvent::count(),
            'videosCount' => Video::count(),
            'womenRunningCount' => WomenRunning::count(),
            'climateCount' => Climate::count(),
            'racesCount' => Race::count(),
            'partnersCount' => Partner::count(),
            'quotesCount' => Quote::count(),

            // Recent activities
            'recentActivities' => $this->getRecentActivities(),
        ];

        return view('admin.dashboard', $data);
    }

    /**
     * Get recent activities for the dashboard
     */
    private function getRecentActivities()
    {
        $activities = [];

        // Get latest stories (last 3)
        $latestStories = Story::latest()->take(3)->get();
        foreach ($latestStories as $story) {
            $activities[] = [
                'icon' => 'book-fill',
                'message' => "New story published: {$story->title}",
                'time' => $story->created_at->diffForHumans(),
            ];
        }

        // Get latest athletes (last 2)
        $latestAthletes = Athlete::latest()->take(2)->get();
        foreach ($latestAthletes as $athlete) {
            $activities[] = [
                'icon' => 'person-plus-fill',
                'message' => "New athlete registered: {$athlete->name}",
                'time' => $athlete->created_at->diffForHumans(),
            ];
        }

        // Get latest events (last 2)
        $latestEvents = RaceEvent::latest()->take(2)->get();
        foreach ($latestEvents as $event) {
            $activities[] = [
                'icon' => 'calendar-event-fill',
                'message' => "New event created: {$event->name}",
                'time' => $event->created_at->diffForHumans(),
            ];
        }

        // Get latest partners (last 2)
        $latestPartners = Partner::latest()->take(2)->get();
        foreach ($latestPartners as $partner) {
            $activities[] = [
                'icon' => 'handshake-fill',
                'message' => "New partner added: {$partner->name}",
                'time' => $partner->created_at->diffForHumans(),
            ];
        }

        // Sort by time and limit to 10 most recent
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 10);
    }
}
