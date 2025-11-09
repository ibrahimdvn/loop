<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    private function getTrendingTopics()
    {
        $userId = Auth::id();
        
        // Kullanıcının etkileşimde bulunduğu gönderileri al
        $userInteractions = $this->getUserInteractions($userId);
        
        // Trend konularını hesapla
        $trendingTopics = $this->calculateTrendingTopics($userInteractions);
        
        return $trendingTopics;
    }
    
    private function getUserInteractions($userId)
    {
        // Kullanıcının beğendiği gönderiler
        $likedPosts = Like::where('user_id', $userId)
            ->with('post')
            ->get()
            ->pluck('post');
            
        // Kullanıcının yorum yaptığı gönderiler
        $commentedPosts = Comment::where('user_id', $userId)
            ->with('post')
            ->get()
            ->pluck('post');
            
        // Kullanıcının kendi gönderileri
        $userPosts = Post::where('user_id', $userId)->get();
        
        // Tüm etkileşimli gönderileri birleştir
        $allPosts = $likedPosts->merge($commentedPosts)->merge($userPosts)->unique('id');
        
        return $allPosts;
    }
    
    private function calculateTrendingTopics($userPosts)
    {
        $trendingData = [];
        
        // Her gönderiyi konu bazında analiz et
        foreach ($userPosts as $post) {
            $topic = $this->analyzePostTopic($post->content);
            
            if ($topic) {
                if (!isset($trendingData[$topic])) {
                    $trendingData[$topic] = [
                        'topic' => $topic,
                        'count' => 0,
                        'category' => $this->categorizeTopic($topic)
                    ];
                }
                $trendingData[$topic]['count']++;
            }
        }
        
        // Sayıya göre sırala ve en popüler 5'i al
        uasort($trendingData, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        $trendingTopics = array_slice($trendingData, 0, 5, true);
        
        // Formatla
        $formattedTrends = [];
        foreach ($trendingTopics as $trend) {
            $formattedTrends[] = [
                'title' => $trend['topic'],
                'category' => $trend['category'],
                'posts' => $trend['count'] . ' gönderi'
            ];
        }
        
        return $formattedTrends;
    }
    
    private function analyzePostTopic($content)
    {
        $content = strtolower($content);
        
        // Konu tanımları ve anahtar kelimeler
        $topics = [
            'Yapay Zeka' => [
                'keywords' => ['yapay zeka', 'ai', 'artificial intelligence', 'makine öğrenme', 'machine learning', 'chatgpt', 'openai', 'neural network', 'deep learning', 'algoritma', 'otomatik', 'robot', 'akıllı sistem'],
                'category' => 'Teknoloji'
            ],
            'Web Geliştirme' => [
                'keywords' => ['web', 'website', 'html', 'css', 'javascript', 'php', 'laravel', 'react', 'vue', 'angular', 'nodejs', 'frontend', 'backend', 'fullstack', 'api', 'database', 'veritabanı'],
                'category' => 'Programlama'
            ],
            'Mobil Uygulama' => [
                'keywords' => ['mobil', 'android', 'ios', 'app', 'uygulama', 'flutter', 'react native', 'swift', 'kotlin', 'mobile', 'telefon', 'tablet'],
                'category' => 'Teknoloji'
            ],
            'Tasarım' => [
                'keywords' => ['tasarım', 'design', 'ui', 'ux', 'grafik', 'görsel', 'logo', 'brand', 'marka', 'photoshop', 'illustrator', 'figma', 'sketch', 'renk', 'tipografi'],
                'category' => 'Tasarım'
            ],
            'İş ve Girişim' => [
                'keywords' => ['iş', 'business', 'girişim', 'startup', 'şirket', 'pazarlama', 'marketing', 'satış', 'müşteri', 'proje', 'yönetim', 'ekip', 'liderlik', 'strateji', 'planlama'],
                'category' => 'İş'
            ],
            'Eğitim' => [
                'keywords' => ['eğitim', 'öğrenme', 'ders', 'kurs', 'okul', 'üniversite', 'öğrenci', 'öğretmen', 'bilgi', 'öğrenmek', 'gelişim', 'beceri', 'yetenek'],
                'category' => 'Eğitim'
            ],
            'Spor ve Sağlık' => [
                'keywords' => ['spor', 'fitness', 'sağlık', 'egzersiz', 'koşu', 'yürüyüş', 'gym', 'salon', 'beslenme', 'diyet', 'sağlıklı', 'antrenman', 'futbol', 'basketbol'],
                'category' => 'Spor'
            ],
            'Müzik ve Sanat' => [
                'keywords' => ['müzik', 'müzik', 'şarkı', 'sanat', 'resim', 'çizim', 'gitar', 'piyano', 'konser', 'festival', 'youtube', 'spotify', 'sanatçı', 'müzisyen'],
                'category' => 'Sanat'
            ],
            'Seyahat' => [
                'keywords' => ['seyahat', 'tatil', 'gezi', 'yolculuk', 'uçak', 'otel', 'şehir', 'ülke', 'turist', 'vize', 'pasaport', 'bavul', 'harita'],
                'category' => 'Seyahat'
            ],
            'Yemek' => [
                'keywords' => ['yemek', 'yemek', 'tarif', 'mutfak', 'restoran', 'cafe', 'kahve', 'çay', 'tatlı', 'pasta', 'pizza', 'burger', 'salata', 'soup'],
                'category' => 'Yemek'
            ]
        ];
        
        // Her konu için skor hesapla
        $scores = [];
        foreach ($topics as $topicName => $topicData) {
            $score = 0;
            foreach ($topicData['keywords'] as $keyword) {
                if (strpos($content, $keyword) !== false) {
                    $score++;
                }
            }
            if ($score > 0) {
                $scores[$topicName] = $score;
            }
        }
        
        // En yüksek skorlu konuyu döndür
        if (!empty($scores)) {
            arsort($scores);
            return array_keys($scores)[0];
        }
        
        return null;
    }
    
    private function categorizeTopic($topic)
    {
        $categories = [
            'Yapay Zeka' => 'Teknoloji',
            'Web Geliştirme' => 'Programlama',
            'Mobil Uygulama' => 'Teknoloji',
            'Tasarım' => 'Tasarım',
            'İş ve Girişim' => 'İş',
            'Eğitim' => 'Eğitim',
            'Spor ve Sağlık' => 'Spor',
            'Müzik ve Sanat' => 'Sanat',
            'Seyahat' => 'Seyahat',
            'Yemek' => 'Yemek'
        ];
        
        return $categories[$topic] ?? 'Genel';
    }
    
}
