<?php

namespace Database\Seeders;

use App\Models\WomenRunning;
use Illuminate\Database\Seeder;

class WomenRunningSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'name' => 'Faith Kipyegon',
                'title' => 'Three-Time Olympic 1500m Champion',
                'description' => 'Faith Cherono Kipyegon is a Kenyan middle-distance runner and three-time Olympic champion in the 1500m. She holds the world record in the 1500m, 5000m, and the mile — making her the greatest female middle-distance runner of all time.',
                'category' => 'featured_story',
                'featured' => true,
                'status' => true,
                'order' => 1,
                'story_content' => 'Faith Kipyegon grew up in Keringet, in the Rift Valley of Kenya. She first came to global attention when she won gold at the 2016 Rio Olympics, and then backed it up with gold at Tokyo 2020. In Paris 2024, she became a three-peat champion. Beyond the track, Faith is a mother, a community leader, and a role model for millions of young girls across Africa who dare to dream.',
                'video_url' => 'https://www.youtube.com/watch?v=1yxoqEKp_hA',
                'icon' => 'trophy',
                'image' => null,
            ],
            [
                'name' => 'Peres Jepchirchir',
                'title' => 'Olympic Marathon Champion & World Record Holder',
                'description' => 'Peres Jepchirchir is a Kenyan marathon runner who won gold at the Tokyo 2020 Olympics. She has set world records in both women-only and mixed marathon races, and is one of the most decorated marathon runners of her generation.',
                'category' => 'featured_story',
                'featured' => true,
                'status' => true,
                'order' => 2,
                'story_content' => 'Peres Jepchirchir comes from the highlands of Nandi County in Kenya\'s Rift Valley. She began competitive running in secondary school and worked her way up through local races before breaking onto the world stage. Her Tokyo gold was a landmark moment, not just for Kenya but for all women marathon runners globally. Away from running, she is an advocate for education and girls\' empowerment.',
                'video_url' => null,
                'icon' => 'medal',
                'image' => null,
            ],
            [
                'name' => 'Hellen Obiri',
                'title' => 'Two-Time World 5000m Champion & Boston Marathon Winner',
                'description' => 'Hellen Onsando Obiri is a Kenyan distance runner who has won back-to-back World Championship 5000m titles and won the 2023 Boston Marathon in her road debut — a remarkable transition from track to road.',
                'category' => 'featured_story',
                'featured' => true,
                'status' => true,
                'order' => 3,
                'story_content' => 'Hellen Obiri\'s journey in athletics is a story of relentless reinvention. After dominating the 5000m on the track for nearly a decade — winning consecutive world titles in 2017 and 2019 — she transitioned to road running and immediately conquered the Boston Marathon. Her ability to adapt and excel across different disciplines makes her one of Kenya\'s most versatile athletes.',
                'video_url' => null,
                'icon' => 'star',
                'image' => null,
            ],
            [
                'name' => 'Brigid Kosgei',
                'title' => 'Former Marathon World Record Holder',
                'description' => 'Brigid Kosgei held the women\'s marathon world record from 2019 to 2023, setting an astonishing time of 2:14:04 at the Chicago Marathon. A two-time London Marathon champion, she represents the pinnacle of Kenyan road running.',
                'category' => 'advocacy',
                'featured' => false,
                'status' => true,
                'order' => 4,
                'story_content' => 'From the hills of Kericho, Brigid Kosgei became one of the most dominant marathon runners in history. Her world record at Chicago in 2019 shocked the athletics world and stood for four years. Kosgei is also a passionate advocate for youth sports in Kenya, regularly returning to her home county to inspire young runners.',
                'video_url' => null,
                'icon' => 'heart',
                'image' => null,
            ],
            [
                'name' => 'Agnes Tirop (In Memoriam)',
                'title' => 'World Cross Country Champion & Marathon Star',
                'description' => 'Agnes Jebet Tirop was a rising star of Kenyan athletics — a two-time World Cross Country champion and a global marathon contender. Her passing in October 2021 was a profound loss for Kenya and the athletics world.',
                'category' => 'mentorship',
                'featured' => false,
                'status' => true,
                'order' => 5,
                'story_content' => 'Agnes Tirop\'s story represents both the bright promise and the fragility of life. A two-time World Cross Country champion at just 25, she was considered one of Kenya\'s greatest hopes for marathon gold. Her memory lives on through the Agnes Tirop World Road Race, a tribute event held annually in Iten, Kenya, and through the many young athletes she inspired to pursue their dreams.',
                'video_url' => null,
                'icon' => 'sparkles',
                'image' => null,
            ],
        ];

        foreach ($entries as $entry) {
            WomenRunning::firstOrCreate(
                ['name' => $entry['name']],
                $entry
            );
        }
    }
}
