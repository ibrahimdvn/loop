<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeder.
     *
     * @return void
     */
    public function run()
    {
        // Get all users or create one if none exist
        $users = User::all();
        
        if ($users->isEmpty()) {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@loop.com',
                'password' => bcrypt('password'),
                'username' => 'demouser'
            ]);
            $users = collect([$user]);
        }

        // Örnek video URL'leri (ücretsiz örnek videolar)
        $videos = [
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
                'content' => 'Harika bir gün! 🌟 #nature #beautiful',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/BigBuckBunny.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
                'content' => 'İnanılmaz anlar ✨ #moments #life',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ElephantsDream.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
                'content' => 'Muhteşem manzara 🏔️ #travel #adventure',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ForBiggerBlazes.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
                'content' => 'Keşfet ve yaşa! 🚀 #explore #journey',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ForBiggerEscapes.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'content' => 'Eğlenceye hazır mısınız? 🎉 #fun #party',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ForBiggerFun.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                'content' => 'Heyecan dolu yolculuk 🏎️ #speed #racing',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ForBiggerJoyrides.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                'content' => 'Sıcak günler ☀️ #summer #sunshine',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/ForBiggerMeltdowns.jpg'
            ],
            [
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
                'content' => 'Sanat ve hayal gücü 🎨 #art #creative',
                'thumbnail' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/images/Sintel.jpg'
            ],
        ];

        foreach ($videos as $index => $videoData) {
            // Rastgele bir kullanıcı seç
            $user = $users->random();

            Post::create([
                'user_id' => $user->id,
                'title' => 'Video ' . ($index + 1),
                'content' => $videoData['content'],
                'video_url' => $videoData['url'],
                'video_thumbnail' => $videoData['thumbnail'],
                'video_duration' => rand(15, 120), // 15-120 saniye arası
                'media_type' => 'video',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('✅ ' . count($videos) . ' örnek video eklendi!');
    }
}

