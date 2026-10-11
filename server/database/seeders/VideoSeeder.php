<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $adminUserId = DB::table('users')->where('email', 'admin@beyondfinishline.com')->value('id') ?? 1;

        $videos = [
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=MaTmZIBnqGk',
                'title' => 'Eliud Kipchoge — No Human Is Limited | INEOS 1:59 Challenge',
                'description' => 'Watch Eliud Kipchoge make history by running a sub-2 hour marathon — a feat of human endurance that inspired the world.',
                'category' => 'athletics',
                'is_featured' => true,
                'added_by' => $adminUserId,
            ],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=1yxoqEKp_hA',
                'title' => 'Faith Kipyegon — The Greatest 1500m Runner of All Time',
                'description' => 'Faith Kipyegon has broken the 1500m world record multiple times. This tribute video celebrates her extraordinary journey from a village in Kenya to Olympic gold.',
                'category' => 'athletics',
                'is_featured' => true,
                'added_by' => $adminUserId,
            ],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=c0P1YHuVNbE',
                'title' => 'Kenyan Athletes at the World Athletics Championships',
                'description' => 'Highlights from Kenyan athletes competing at the World Athletics Championships, showcasing the depth and talent of Kenya\'s track and field programme.',
                'category' => 'athletics',
                'is_featured' => true,
                'added_by' => $adminUserId,
            ],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=hPSWHPmJ-Ek',
                'title' => 'Peres Jepchirchir Wins Olympic Marathon Gold | Tokyo 2020',
                'description' => 'Peres Jepchirchir becomes Olympic marathon champion in Tokyo, continuing Kenya\'s proud marathon tradition on the world stage.',
                'category' => 'athletics',
                'is_featured' => false,
                'added_by' => $adminUserId,
            ],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=3fYOCFVLqQM',
                'title' => 'Beyond the Finish Line — Athlete Stories from Kenya',
                'description' => 'Eagle Sports presents stories of Kenyan athletes who continue to inspire communities long after they cross the finish line.',
                'category' => 'athletics',
                'is_featured' => false,
                'added_by' => $adminUserId,
            ],
        ];

        foreach ($videos as $video) {
            Video::firstOrCreate(
                ['youtube_url' => $video['youtube_url']],
                $video
            );
        }
    }
}
