<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Get platform overview statistics
     */
    public function getPlatformOverview()
    {
        return Cache::remember('analytics_platform_overview', 60, function () {
            return [
                'total_users' => User::count(),
                'total_posts' => Post::count(),
                'total_likes' => Like::count(),
                'total_comments' => Comment::count(),
                'total_notifications' => Notification::count(),
                'active_users_today' => $this->getActiveUsersToday(),
                'new_users_today' => $this->getNewUsersToday(),
                'posts_today' => $this->getPostsToday(),
            ];
        });
    }

    /**
     * Get user engagement metrics
     */
    public function getUserEngagement($period = '7d')
    {
        $cacheKey = "analytics_user_engagement_{$period}";
        
        return Cache::remember($cacheKey, 30, function () use ($period) {
            $days = $this->getDaysFromPeriod($period);
            $startDate = Carbon::now()->subDays($days);
            
            return [
                'daily_active_users' => $this->getDailyActiveUsers($startDate),
                'user_retention' => $this->getUserRetention($startDate),
                'engagement_rate' => $this->getEngagementRate($startDate),
                'average_session_duration' => $this->getAverageSessionDuration($startDate),
            ];
        });
    }

    /**
     * Get content performance metrics
     */
    public function getContentPerformance($period = '7d')
    {
        $cacheKey = "analytics_content_performance_{$period}";
        
        return Cache::remember($cacheKey, 30, function () use ($period) {
            $days = $this->getDaysFromPeriod($period);
            $startDate = Carbon::now()->subDays($days);
            
            return [
                'top_posts' => $this->getTopPosts($startDate),
                'trending_hashtags' => $this->getTrendingHashtags($startDate),
                'content_types' => $this->getContentTypesDistribution($startDate),
                'engagement_by_content_type' => $this->getEngagementByContentType($startDate),
            ];
        });
    }

    /**
     * Get user growth metrics
     */
    public function getUserGrowth($period = '30d')
    {
        $cacheKey = "analytics_user_growth_{$period}";
        
        return Cache::remember($cacheKey, 60, function () use ($period) {
            $days = $this->getDaysFromPeriod($period);
            $startDate = Carbon::now()->subDays($days);
            
            return [
                'user_registrations' => $this->getUserRegistrations($startDate),
                'user_retention_rates' => $this->getUserRetentionRates($startDate),
                'geographic_distribution' => $this->getGeographicDistribution(),
                'user_verification_rate' => $this->getUserVerificationRate(),
            ];
        });
    }

    /**
     * Get real-time metrics
     */
    public function getRealTimeMetrics()
    {
        return Cache::remember('analytics_realtime', 5, function () {
            return [
                'online_users' => $this->getOnlineUsers(),
                'posts_last_hour' => $this->getPostsLastHour(),
                'likes_last_hour' => $this->getLikesLastHour(),
                'comments_last_hour' => $this->getCommentsLastHour(),
                'new_users_last_hour' => $this->getNewUsersLastHour(),
            ];
        });
    }

    /**
     * Get user-specific analytics
     */
    public function getUserAnalytics($userId)
    {
        $cacheKey = "analytics_user_{$userId}";
        
        return Cache::remember($cacheKey, 30, function () use ($userId) {
            $user = User::find($userId);
            if (!$user) return null;
            
            return [
                'profile_views' => $this->getUserProfileViews($userId),
                'post_performance' => $this->getUserPostPerformance($userId),
                'follower_growth' => $this->getUserFollowerGrowth($userId),
                'engagement_rate' => $this->getUserEngagementRate($userId),
                'best_posting_times' => $this->getUserBestPostingTimes($userId),
                'top_hashtags' => $this->getUserTopHashtags($userId),
            ];
        });
    }

    /**
     * Get active users today
     */
    protected function getActiveUsersToday()
    {
        return User::whereDate('updated_at', Carbon::today())->count();
    }

    /**
     * Get new users today
     */
    protected function getNewUsersToday()
    {
        return User::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Get posts today
     */
    protected function getPostsToday()
    {
        return Post::whereDate('created_at', Carbon::today())->count();
    }

    /**
     * Get daily active users
     */
    protected function getDailyActiveUsers($startDate)
    {
        return User::where('updated_at', '>=', $startDate)
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get user retention
     */
    protected function getUserRetention($startDate)
    {
        $totalUsers = User::where('created_at', '<=', $startDate)->count();
        $activeUsers = User::where('updated_at', '>=', $startDate)->count();
        
        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
    }

    /**
     * Get engagement rate
     */
    protected function getEngagementRate($startDate)
    {
        $totalPosts = Post::where('created_at', '>=', $startDate)->count();
        $totalInteractions = Like::where('created_at', '>=', $startDate)->count() + 
                           Comment::where('created_at', '>=', $startDate)->count();
        
        return $totalPosts > 0 ? round(($totalInteractions / $totalPosts), 2) : 0;
    }

    /**
     * Get average session duration (mock data for now)
     */
    protected function getAverageSessionDuration($startDate)
    {
        // This would typically come from session tracking
        return rand(300, 1800); // 5-30 minutes in seconds
    }

    /**
     * Get top posts
     */
    protected function getTopPosts($startDate)
    {
        return Post::where('created_at', '>=', $startDate)
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count + comments_count) DESC')
            ->limit(10)
            ->get();
    }

    /**
     * Get trending hashtags
     */
    protected function getTrendingHashtags($startDate)
    {
        return Post::where('created_at', '>=', $startDate)
            ->whereNotNull('hashtags')
            ->get()
            ->pluck('hashtags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(20)
            ->toArray();
    }

    /**
     * Get content types distribution
     */
    protected function getContentTypesDistribution($startDate)
    {
        return Post::where('created_at', '>=', $startDate)
            ->selectRaw('media_type, COUNT(*) as count')
            ->groupBy('media_type')
            ->get()
            ->pluck('count', 'media_type')
            ->toArray();
    }

    /**
     * Get engagement by content type
     */
    protected function getEngagementByContentType($startDate)
    {
        return Post::where('created_at', '>=', $startDate)
            ->withCount(['likes', 'comments'])
            ->get()
            ->groupBy('media_type')
            ->map(function ($posts) {
                return [
                    'total_posts' => $posts->count(),
                    'total_likes' => $posts->sum('likes_count'),
                    'total_comments' => $posts->sum('comments_count'),
                    'avg_engagement' => $posts->avg(function ($post) {
                        return $post->likes_count + $post->comments_count;
                    })
                ];
            });
    }

    /**
     * Get user registrations
     */
    protected function getUserRegistrations($startDate)
    {
        return User::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Get user retention rates
     */
    protected function getUserRetentionRates($startDate)
    {
        $cohorts = [];
        $periods = [1, 7, 14, 30]; // days
        
        foreach ($periods as $period) {
            $cohortDate = Carbon::now()->subDays($period);
            $registeredUsers = User::whereDate('created_at', $cohortDate)->count();
            $activeUsers = User::whereDate('created_at', $cohortDate)
                ->where('updated_at', '>=', $startDate)
                ->count();
            
            $cohorts[$period] = $registeredUsers > 0 ? 
                round(($activeUsers / $registeredUsers) * 100, 2) : 0;
        }
        
        return $cohorts;
    }

    /**
     * Get geographic distribution (mock data)
     */
    protected function getGeographicDistribution()
    {
        return [
            'Turkey' => rand(40, 60),
            'USA' => rand(10, 20),
            'Germany' => rand(5, 15),
            'UK' => rand(5, 10),
            'France' => rand(3, 8),
            'Other' => rand(10, 20),
        ];
    }

    /**
     * Get user verification rate
     */
    protected function getUserVerificationRate()
    {
        $totalUsers = User::count();
        $verifiedUsers = User::where('is_verified', true)->count();
        
        return $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0;
    }

    /**
     * Get online users (mock data)
     */
    protected function getOnlineUsers()
    {
        return User::where('updated_at', '>=', Carbon::now()->subMinutes(15))->count();
    }

    /**
     * Get posts last hour
     */
    protected function getPostsLastHour()
    {
        return Post::where('created_at', '>=', Carbon::now()->subHour())->count();
    }

    /**
     * Get likes last hour
     */
    protected function getLikesLastHour()
    {
        return Like::where('created_at', '>=', Carbon::now()->subHour())->count();
    }

    /**
     * Get comments last hour
     */
    protected function getCommentsLastHour()
    {
        return Comment::where('created_at', '>=', Carbon::now()->subHour())->count();
    }

    /**
     * Get new users last hour
     */
    protected function getNewUsersLastHour()
    {
        return User::where('created_at', '>=', Carbon::now()->subHour())->count();
    }

    /**
     * Get user profile views (mock data)
     */
    protected function getUserProfileViews($userId)
    {
        return rand(100, 10000);
    }

    /**
     * Get user post performance
     */
    protected function getUserPostPerformance($userId)
    {
        return Post::where('user_id', $userId)
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get user follower growth
     */
    protected function getUserFollowerGrowth($userId)
    {
        // This would require a followers_history table for accurate tracking
        return [
            'current_followers' => User::find($userId)->followers()->count(),
            'growth_rate' => rand(5, 25) . '%',
        ];
    }

    /**
     * Get user engagement rate
     */
    protected function getUserEngagementRate($userId)
    {
        $user = User::find($userId);
        $totalPosts = $user->posts()->count();
        $totalLikes = $user->posts()->withCount('likes')->get()->sum('likes_count');
        $totalComments = $user->posts()->withCount('comments')->get()->sum('comments_count');
        
        return $totalPosts > 0 ? round(($totalLikes + $totalComments) / $totalPosts, 2) : 0;
    }

    /**
     * Get user best posting times
     */
    protected function getUserBestPostingTimes($userId)
    {
        return Post::where('user_id', $userId)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get user top hashtags
     */
    protected function getUserTopHashtags($userId)
    {
        return Post::where('user_id', $userId)
            ->whereNotNull('hashtags')
            ->get()
            ->pluck('hashtags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10)
            ->toArray();
    }

    /**
     * Get days from period string
     */
    protected function getDaysFromPeriod($period)
    {
        switch ($period) {
            case '1d': return 1;
            case '7d': return 7;
            case '30d': return 30;
            case '90d': return 90;
            default: return 7;
        }
    }

    /**
     * Clear analytics cache
     */
    public function clearCache()
    {
        Cache::forget('analytics_platform_overview');
        Cache::forget('analytics_realtime');
        
        $periods = ['1d', '7d', '30d', '90d'];
        foreach ($periods as $period) {
            Cache::forget("analytics_user_engagement_{$period}");
            Cache::forget("analytics_content_performance_{$period}");
            Cache::forget("analytics_user_growth_{$period}");
        }
    }
}
