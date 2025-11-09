<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrendingService
{
    /**
     * Calculate trending score for hashtags
     * Algorithm considers:
     * - Post count (how many posts use this hashtag)
     * - Engagement (likes + comments)
     * - Recency (newer posts get higher score)
     * - Growth rate (how fast it's growing)
     */
    public function getTrendingHashtags($limit = 10)
    {
        return Cache::remember('trending_hashtags', 15, function () use ($limit) {
            $timeframes = [
                'hour' => Carbon::now()->subHour(),
                'day' => Carbon::now()->subDay(),
                'week' => Carbon::now()->subWeek(),
            ];

            $hashtags = [];

            // Get posts from last week with hashtags
            $posts = Post::where('created_at', '>=', $timeframes['week'])
                ->whereNotNull('hashtags')
                ->with(['likes', 'comments'])
                ->get();

            // Calculate scores for each hashtag
            $hashtagStats = [];

            foreach ($posts as $post) {
                if (!$post->hashtags) continue;

                foreach ($post->hashtags as $hashtag) {
                    if (!isset($hashtagStats[$hashtag])) {
                        $hashtagStats[$hashtag] = [
                            'hashtag' => $hashtag,
                            'post_count' => 0,
                            'engagement' => 0,
                            'hour_posts' => 0,
                            'day_posts' => 0,
                            'week_posts' => 0,
                            'recent_engagement' => 0,
                            'users' => [],
                        ];
                    }

                    $stats = &$hashtagStats[$hashtag];
                    $stats['post_count']++;
                    $stats['engagement'] += $post->likes()->count() + $post->comments()->count();
                    $stats['users'][$post->user_id] = true;

                    // Count posts in different timeframes
                    if ($post->created_at >= $timeframes['hour']) {
                        $stats['hour_posts']++;
                        $stats['recent_engagement'] += $post->likes()->count() + $post->comments()->count();
                    }
                    if ($post->created_at >= $timeframes['day']) {
                        $stats['day_posts']++;
                    }
                    if ($post->created_at >= $timeframes['week']) {
                        $stats['week_posts']++;
                    }
                }
            }

            // Calculate trending score
            foreach ($hashtagStats as $hashtag => &$stats) {
                $stats['unique_users'] = count($stats['users']);
                unset($stats['users']);

                // Trending Score Formula:
                // Score = (PostCount * 10) + (Engagement * 5) + (HourPosts * 100) + (DayPosts * 50) + (UniqueUsers * 20)
                $score = 0;
                $score += $stats['post_count'] * 10;
                $score += $stats['engagement'] * 5;
                $score += $stats['hour_posts'] * 100; // Recent activity is very important
                $score += $stats['day_posts'] * 50;
                $score += $stats['unique_users'] * 20; // Diversity of users matters
                $score += $stats['recent_engagement'] * 15; // Recent engagement matters

                // Growth rate bonus
                if ($stats['week_posts'] > 0) {
                    $growthRate = $stats['day_posts'] / $stats['week_posts'];
                    $score += $growthRate * 30;
                }

                $stats['score'] = round($score, 2);
                $stats['display_name'] = '#' . $hashtag;
            }

            // Sort by score
            usort($hashtagStats, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            return array_slice($hashtagStats, 0, $limit);
        });
    }

    /**
     * Get trending posts based on engagement velocity
     */
    public function getTrendingPosts($limit = 20)
    {
        return Cache::remember('trending_posts', 10, function () use ($limit) {
            $posts = Post::where('created_at', '>=', Carbon::now()->subDays(3))
                ->withCount(['likes', 'comments'])
                ->with('user')
                ->get();

            $scoredPosts = [];

            foreach ($posts as $post) {
                // Calculate age in hours
                $ageInHours = $post->created_at->diffInHours(now()) ?: 1;
                
                // Engagement velocity (engagement per hour)
                $velocity = ($post->likes_count + ($post->comments_count * 2)) / $ageInHours;
                
                // Penalty for older posts
                $agePenalty = 1 / (1 + ($ageInHours / 24));
                
                // Final score
                $score = $velocity * $agePenalty * 100;
                
                // Bonus for verified users
                if ($post->user->is_verified) {
                    $score *= 1.2;
                }

                $scoredPosts[] = [
                    'post' => $post,
                    'score' => $score,
                    'velocity' => round($velocity, 2),
                    'age_hours' => $ageInHours,
                ];
            }

            // Sort by score
            usort($scoredPosts, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            return array_slice($scoredPosts, 0, $limit);
        });
    }

    /**
     * Get trending topics with categories
     */
    public function getTrendingTopics($limit = 15)
    {
        return Cache::remember('trending_topics', 20, function () use ($limit) {
            $hashtags = $this->getTrendingHashtags($limit);
            
            $topics = [];
            
            foreach ($hashtags as $hashtagData) {
                $category = $this->categorizeHashtag($hashtagData);
                
                $topics[] = [
                    'hashtag' => $hashtagData['hashtag'],
                    'display_name' => $hashtagData['display_name'],
                    'post_count' => $hashtagData['post_count'],
                    'score' => $hashtagData['score'],
                    'category' => $category,
                    'trend_indicator' => $this->getTrendIndicator($hashtagData),
                    'stats' => [
                        'hour_posts' => $hashtagData['hour_posts'],
                        'day_posts' => $hashtagData['day_posts'],
                        'engagement' => $hashtagData['engagement'],
                        'unique_users' => $hashtagData['unique_users'],
                    ]
                ];
            }

            return $topics;
        });
    }

    /**
     * Categorize hashtag based on content
     */
    protected function categorizeHashtag($hashtagData)
    {
        $hashtag = strtolower($hashtagData['hashtag']);
        
        // Technology keywords
        $tech = ['teknoloji', 'tech', 'ai', 'yapay', 'zeka', 'kod', 'program', 'developer', 'software'];
        // Sports keywords
        $sports = ['spor', 'futbol', 'basketbol', 'sports', 'football', 'basketball'];
        // Entertainment keywords
        $entertainment = ['müzik', 'film', 'dizi', 'music', 'movie', 'series', 'netflix'];
        // News keywords
        $news = ['haber', 'news', 'gündem', 'breaking', 'son', 'dakika'];
        // Lifestyle keywords
        $lifestyle = ['hayat', 'yaşam', 'lifestyle', 'yemek', 'seyahat', 'food', 'travel'];

        foreach ($tech as $keyword) {
            if (strpos($hashtag, $keyword) !== false) return 'Teknoloji';
        }
        foreach ($sports as $keyword) {
            if (strpos($hashtag, $keyword) !== false) return 'Spor';
        }
        foreach ($entertainment as $keyword) {
            if (strpos($hashtag, $keyword) !== false) return 'Eğlence';
        }
        foreach ($news as $keyword) {
            if (strpos($hashtag, $keyword) !== false) return 'Haberler';
        }
        foreach ($lifestyle as $keyword) {
            if (strpos($hashtag, $keyword) !== false) return 'Yaşam';
        }

        return 'Genel';
    }

    /**
     * Get trend indicator (rising, hot, stable)
     */
    protected function getTrendIndicator($hashtagData)
    {
        // Very hot if many posts in last hour
        if ($hashtagData['hour_posts'] >= 5) {
            return ['type' => 'hot', 'icon' => '🔥', 'label' => 'Çok Gündemde'];
        }

        // Rising if growing fast
        if ($hashtagData['day_posts'] > 0 && $hashtagData['week_posts'] > 0) {
            $growthRate = $hashtagData['day_posts'] / $hashtagData['week_posts'];
            if ($growthRate > 0.5) {
                return ['type' => 'rising', 'icon' => '📈', 'label' => 'Yükseliyor'];
            }
        }

        // New if mostly recent
        if ($hashtagData['day_posts'] >= $hashtagData['week_posts'] * 0.8) {
            return ['type' => 'new', 'icon' => '✨', 'label' => 'Yeni'];
        }

        return ['type' => 'stable', 'icon' => '💬', 'label' => 'Popüler'];
    }

    /**
     * Get personalized trending for user
     */
    public function getPersonalizedTrending($userId, $limit = 10)
    {
        $cacheKey = "personalized_trending_{$userId}";
        
        return Cache::remember($cacheKey, 30, function () use ($userId, $limit) {
            $user = User::find($userId);
            if (!$user) return [];

            // Get hashtags from user's posts
            $userHashtags = Post::where('user_id', $userId)
                ->whereNotNull('hashtags')
                ->get()
                ->pluck('hashtags')
                ->flatten()
                ->unique()
                ->toArray();

            // Get hashtags from followed users
            $followingIds = $user->following()->pluck('following_id');
            $followingHashtags = Post::whereIn('user_id', $followingIds)
                ->whereNotNull('hashtags')
                ->where('created_at', '>=', Carbon::now()->subWeek())
                ->get()
                ->pluck('hashtags')
                ->flatten()
                ->countBy()
                ->toArray();

            // Get global trending
            $globalTrending = $this->getTrendingHashtags(20);

            // Score and combine
            $personalizedScores = [];

            foreach ($globalTrending as $trend) {
                $score = $trend['score'];
                
                // Boost if user has used this hashtag
                if (in_array($trend['hashtag'], $userHashtags)) {
                    $score *= 1.5;
                }
                
                // Boost if following users use it
                if (isset($followingHashtags[$trend['hashtag']])) {
                    $score *= (1 + ($followingHashtags[$trend['hashtag']] / 10));
                }

                $personalizedScores[] = array_merge($trend, ['personalized_score' => $score]);
            }

            // Sort by personalized score
            usort($personalizedScores, function ($a, $b) {
                return $b['personalized_score'] <=> $a['personalized_score'];
            });

            return array_slice($personalizedScores, 0, $limit);
        });
    }

    /**
     * Get trending statistics for admin
     */
    public function getTrendingStatistics()
    {
        return Cache::remember('trending_statistics', 60, function () {
            $hashtags = $this->getTrendingHashtags(50);
            
            $totalPosts = array_sum(array_column($hashtags, 'post_count'));
            $totalEngagement = array_sum(array_column($hashtags, 'engagement'));
            $totalUsers = array_sum(array_column($hashtags, 'unique_users'));

            $categories = [];
            foreach ($hashtags as $tag) {
                $category = $this->categorizeHashtag($tag);
                if (!isset($categories[$category])) {
                    $categories[$category] = 0;
                }
                $categories[$category]++;
            }

            return [
                'total_trending_topics' => count($hashtags),
                'total_posts' => $totalPosts,
                'total_engagement' => $totalEngagement,
                'total_unique_users' => $totalUsers,
                'categories' => $categories,
                'top_hashtag' => $hashtags[0] ?? null,
                'most_engaging' => collect($hashtags)->sortByDesc('engagement')->first(),
                'fastest_growing' => collect($hashtags)->sortByDesc('hour_posts')->first(),
            ];
        });
    }

    /**
     * Clear trending cache
     */
    public function clearCache()
    {
        Cache::forget('trending_hashtags');
        Cache::forget('trending_posts');
        Cache::forget('trending_topics');
        Cache::forget('trending_statistics');
        
        // Clear personalized cache for all users
        $users = User::all();
        foreach ($users as $user) {
            Cache::forget("personalized_trending_{$user->id}");
        }
    }
}

