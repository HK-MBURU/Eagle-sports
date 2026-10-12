<?php

namespace Database\Seeders;

use App\Models\RaceEvent;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder
{
    public function run(): void
    {
        RaceEvent::where('is_calendar_event', true)->delete();

        $events = [
            [
                'title'              => 'Youth Olympic Games — Dakar 2026',
                'subtitle'           => 'Youth Olympic Games',
                'event_date'         => '2026-11-08',
                'location'           => 'Dakar, Senegal',
                'start_time'         => '09:00',
                'distance_options'   => 'Multiple athletics disciplines',
                'description'        => 'The 2026 Youth Olympic Games athletics programme in Dakar, Senegal.',
                'cal_top'            => '08',
                'cal_bottom'         => 'NOV*',
                'cal_note'           => 'Confirm dates and programme with official organisers.',
                'is_calendar_event'  => true,
                'is_featured'        => true,
                'is_active'          => true,
                'created_by'         => 1,
            ],
            [
                'title'              => 'Summer Olympic Games — LA28',
                'subtitle'           => 'Summer Olympics',
                'event_date'         => '2028-07-14',
                'location'           => 'Los Angeles, United States',
                'start_time'         => '09:00',
                'distance_options'   => 'Full Olympic athletics programme',
                'description'        => 'The 2028 Summer Olympic Games in Los Angeles, United States.',
                'cal_top'            => '2028',
                'cal_bottom'         => 'SUMMER',
                'cal_note'           => 'Competition-specific athletics schedule to be added.',
                'is_calendar_event'  => true,
                'is_featured'        => true,
                'is_active'          => true,
                'created_by'         => 1,
            ],
            [
                'title'              => 'Diamond League & Continental Tour',
                'subtitle'           => 'Global Athletics Circuit',
                'event_date'         => '2026-05-01',
                'location'           => 'Multiple venues worldwide',
                'start_time'         => '09:00',
                'distance_options'   => 'Track & field disciplines',
                'description'        => 'The 2026 Diamond League and Continental Tour season.',
                'cal_top'            => '2026',
                'cal_bottom'         => 'TOUR',
                'cal_note'           => 'Individual meetings, venues and dates to be maintained from verified official calendars.',
                'is_calendar_event'  => true,
                'is_featured'        => false,
                'is_active'          => true,
                'created_by'         => 1,
            ],
            [
                'title'              => 'Marathons, road races and national meets',
                'subtitle'           => 'Road Racing & National Championships',
                'event_date'         => '2026-01-01',
                'location'           => 'Kenya & Africa',
                'start_time'         => '07:00',
                'distance_options'   => 'Marathon, half marathon, road races',
                'description'        => 'Kenyan and African road races, marathons and national championship meets throughout the year.',
                'cal_top'            => 'ALL',
                'cal_bottom'         => 'YEAR',
                'cal_note'           => 'Filter by date, country, discipline and competition series in the full calendar.',
                'is_calendar_event'  => true,
                'is_featured'        => false,
                'is_active'          => true,
                'created_by'         => 1,
            ],
        ];

        foreach ($events as $event) {
            RaceEvent::create($event);
        }

        $this->command->info('Competition calendar seeded: ' . count($events) . ' events');
    }
}
