<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResultsCentreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('result_entries')->truncate();

        $entries = [
            // Bingwafest 2025 results
            [
                'competition'      => 'Bingwafest 2025 Central Edition',
                'event_discipline' => "Men's 10,000m",
                'performance'      => '28:45.32',
                'athlete_name'     => 'Mathew Kipkoech',
                'athlete_country'  => 'KEN',
                'series'           => 'Road Race',
                'result_status'    => 'official',
                'race_date'        => '2025-12-20',
                'location'         => 'Kinoru Stadium, Meru',
                'is_featured'      => true,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            [
                'competition'      => 'Bingwafest 2025 Central Edition',
                'event_discipline' => "Women's 10,000m",
                'performance'      => '32:14.10',
                'athlete_name'     => 'Loice Chemnung',
                'athlete_country'  => 'KEN',
                'series'           => 'Road Race',
                'result_status'    => 'official',
                'race_date'        => '2025-12-20',
                'location'         => 'Kinoru Stadium, Meru',
                'is_featured'      => true,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            // Diamond League
            [
                'competition'      => 'Diamond League — Zurich',
                'event_discipline' => "Men's 1500m",
                'performance'      => '3:31.45',
                'athlete_name'     => 'Timothy Cheruiyot',
                'athlete_country'  => 'KEN',
                'series'           => 'Diamond League',
                'result_status'    => 'official',
                'race_date'        => '2025-08-29',
                'location'         => 'Zurich, Switzerland',
                'is_featured'      => true,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            [
                'competition'      => 'Diamond League — Eugene',
                'event_discipline' => "Women's 5000m",
                'performance'      => '14:28.11',
                'athlete_name'     => 'Faith Kipyegon',
                'athlete_country'  => 'KEN',
                'series'           => 'Diamond League',
                'result_status'    => 'official',
                'race_date'        => '2025-09-07',
                'location'         => 'Eugene, Oregon, USA',
                'is_featured'      => true,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            // Continental Tour
            [
                'competition'      => 'Continental Tour — Nairobi',
                'event_discipline' => "Men's 800m",
                'performance'      => '1:43.25',
                'athlete_name'     => 'Emmanuel Korir',
                'athlete_country'  => 'KEN',
                'series'           => 'Continental Tour',
                'result_status'    => 'official',
                'race_date'        => '2025-07-15',
                'location'         => 'Moi Stadium, Nairobi',
                'is_featured'      => false,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            [
                'competition'      => 'Continental Tour — Nairobi',
                'event_discipline' => "Women's 800m",
                'performance'      => '1:58.40',
                'athlete_name'     => 'Mary Moraa',
                'athlete_country'  => 'KEN',
                'series'           => 'Continental Tour',
                'result_status'    => 'official',
                'race_date'        => '2025-07-15',
                'location'         => 'Moi Stadium, Nairobi',
                'is_featured'      => false,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            // Road Races
            [
                'competition'      => 'Nairobi City Marathon',
                'event_discipline' => 'Men\'s Marathon',
                'performance'      => '2:05:48',
                'athlete_name'     => 'Eliud Kipchoge',
                'athlete_country'  => 'KEN',
                'series'           => 'Road Race',
                'result_status'    => 'official',
                'race_date'        => '2025-10-26',
                'location'         => 'Nairobi, Kenya',
                'is_featured'      => true,
                'is_published'     => true,
                'created_by'       => 1,
            ],
            [
                'competition'      => 'Standard Chartered Nairobi Marathon',
                'event_discipline' => "Women's Half Marathon",
                'performance'      => '1:05:42',
                'athlete_name'     => 'Peres Jepchirchir',
                'athlete_country'  => 'KEN',
                'series'           => 'Road Race',
                'result_status'    => 'official',
                'race_date'        => '2025-10-26',
                'location'         => 'Nairobi, Kenya',
                'is_featured'      => false,
                'is_published'     => true,
                'created_by'       => 1,
            ],
        ];

        DB::table('result_entries')->insert($entries);

        $this->command->info('Results Centre seeded: ' . count($entries) . ' entries');
    }
}
