<?php

namespace Database\Seeders;

use App\Models\RaceEvent;
use Illuminate\Database\Seeder;

class FlagshipEventSeeder extends Seeder
{
    public function run(): void
    {
        // Remove any existing flagship entries to avoid duplicates
        RaceEvent::where('is_flagship', true)->delete();

        RaceEvent::create([
            'title'                => 'DAKAR 2026',
            'subtitle'             => 'Youth Olympic Games',
            'eyebrow_text'         => 'FLAGSHIP COVERAGE · 2026',
            'coverage_description' => 'A dedicated home for the countdown, Team Kenya, athlete profiles, schedules, results, medal updates, interviews, videos and photographs. The section remains available as an archive after the Games.',
            'event_date'           => '2026-10-31',
            'location'             => 'Dakar, Senegal',
            'start_time'           => '09:00',
            'expected_runners'     => null,
            'distance_options'     => 'Multiple athletics disciplines',
            'image'                => null,
            'description'          => 'The 2026 Youth Olympic Games (Dakar 2026) is the fourth edition of the Summer Youth Olympic Games, hosted in Dakar, Senegal. Kenyan athletes will compete across sprints, middle-distance, long-distance, jumps and throws.',
            'registration_link'    => null,
            'is_featured'          => true,
            'is_flagship'          => true,
            'is_active'            => true,
            'art_image_url'        => 'https://images.unsplash.com/photo-1526676037777-05a232554f77?auto=format&fit=crop&w=1200&q=85',
            'art_caption'          => 'ROAD TO DAKAR · TEAM KENYA',
            'quick_links'          => json_encode([
                ['label' => 'Team Kenya & Profiles', 'href' => '/stories/profiles'],
                ['label' => 'Schedule & Results',    'href' => '/races/results'],
                ['label' => 'Videos & Interviews',   'href' => '/media/interviews'],
                ['label' => 'Photo Gallery',          'href' => '/media/photos'],
                ['label' => 'Event Calendar',         'href' => '/races/calendar'],
                ['label' => 'Post-Games Legacy',      'href' => '/stories/inspire'],
            ]),
            'created_by' => 1,
        ]);

        $this->command->info('Flagship event seeded: DAKAR 2026 Youth Olympic Games');
    }
}
