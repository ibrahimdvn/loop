<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Post;
use App\Models\User;
use App\Models\Notification;

class CacheService
{
    /**
     * Cache duration in minutes
     */
    const CACHE_DURATION = [
        'posts' => 30,
        'users' => 60,
        'notifications' => 15,
        'trending' => 60,
        'suggestions' => 120,
    ];

    /**
     * Cache popular posts
     */
    public function cachePopularPosts($limit = 10)
    {
        $cacheKey = "popular_posts_{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['posts'], function () use ($limit) {
            return Post::with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('likes_count', 'desc')
                ->orderBy('comments_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cache trending hashtags
     */
    public function cacheTrendingHashtags($limit = 10)
    {
        $cacheKey = "trending_hashtags_{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['trending'], function () use ($limit) {
            $hashtags = Post::whereNotNull('hashtags')
                ->get()
                ->pluck('hashtags')
                ->flatten()
                ->filter()
                ->countBy()
                ->sortDesc()
                ->take($limit)
                ->keys()
                ->toArray();
                
            return $hashtags;
        });
    }

    /**
     * Cache user suggestions
     */
    public function cacheUserSuggestions($userId, $limit = 5)
    {
        $cacheKey = "user_suggestions_{$userId}_{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['suggestions'], function () use ($userId, $limit) {
            $user = User::find($userId);
            if (!$user) return collect();
            
            // Get users that the current user is not following
            $followingIds = $user->following()->pluck('following_id');
            $followingIds[] = $userId; // Exclude self
            
            return User::whereNotIn('id', $followingIds)
                ->withCount(['followers', 'posts'])
                ->orderBy('followers_count', 'desc')
                ->orderBy('posts_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cache user notifications
     */
    public function cacheUserNotifications($userId, $limit = 20)
    {
        $cacheKey = "user_notifications_{$userId}_{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['notifications'], function () use ($userId, $limit) {
            return Notification::where('user_id', $userId)
                ->with(['fromUser', 'post'])
                ->latest()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Cache user stats
     */
    public function cacheUserStats($userId)
    {
        $cacheKey = "user_stats_{$userId}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['users'], function () use ($userId) {
            $user = User::find($userId);
            if (!$user) return null;
            
            return [
                'posts_count' => $user->posts()->count(),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'likes_received' => $user->posts()->withCount('likes')->get()->sum('likes_count'),
                'comments_received' => $user->posts()->withCount('comments')->get()->sum('comments_count'),
            ];
        });
    }

    /**
     * Cache post details with relationships
     */
    public function cachePostDetails($postId)
    {
        $cacheKey = "post_details_{$postId}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['posts'], function () use ($postId) {
            return Post::with(['user', 'likes', 'comments.user'])
                ->withCount(['likes', 'comments'])
                ->find($postId);
        });
    }

    /**
     * Cache user profile data
     */
    public function cacheUserProfile($userId)
    {
        $cacheKey = "user_profile_{$userId}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['users'], function () use ($userId) {
            return User::withCount(['posts', 'followers', 'following'])
                ->find($userId);
        });
    }

    /**
     * Cache recent posts for home feed
     */
    public function cacheHomeFeed($userId, $page = 1, $limit = 10)
    {
        $cacheKey = "home_feed_{$userId}_page_{$page}_{$limit}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION['posts'], function () use ($userId, $page, $limit) {
            $user = User::find($userId);
            if (!$user) return collect();
            
            // Get posts from users that the current user follows
            $followingIds = $user->following()->pluck('following_id');
            $followingIds[] = $userId; // Include own posts
            
            return Post::whereIn('user_id', $followingIds)
                ->where(function($query) {
                    // Exclude video posts (reels)
                    $query->whereNull('video')
                          ->whereNull('video_url');
                })
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->paginate($limit, ['*'], 'page', $page);
        });
    }

    /**
     * Invalidate cache for specific user
     */
    public function invalidateUserCache($userId)
    {
        $patterns = [
            "user_suggestions_{$userId}_*",
            "user_notifications_{$userId}_*",
            "user_stats_{$userId}",
            "user_profile_{$userId}",
            "home_feed_{$userId}_*",
        ];
        
        foreach ($patterns as $pattern) {
            $this->invalidateCachePattern($pattern);
        }
    }

    /**
     * Invalidate cache for specific post
     */
    public function invalidatePostCache($postId)
    {
        $patterns = [
            "post_details_{$postId}",
            "popular_posts_*",
            "home_feed_*",
        ];
        
        foreach ($patterns as $pattern) {
            $this->invalidateCachePattern($pattern);
        }
    }

    /**
     * Invalidate cache pattern using Redis
     */
    protected function invalidateCachePattern($pattern)
    {
        try {
            if (class_exists('Redis')) {
                $keys = Redis::keys($pattern);
                if (!empty($keys)) {
                    Redis::del($keys);
                }
            } else {
                // Fallback to Laravel cache
                Cache::flush();
            }
        } catch (\Exception $e) {
            // Fallback to Laravel cache
            Cache::flush();
        }
    }

    /**
     * Warm up cache for better performance
     */
    public function warmUpCache()
    {
        // Cache popular posts
        $this->cachePopularPosts(20);
        
        // Cache trending hashtags
        $this->cacheTrendingHashtags(15);
        
        // Cache user suggestions for active users
        $activeUsers = User::whereHas('posts')->limit(50)->get();
        foreach ($activeUsers as $user) {
            $this->cacheUserSuggestions($user->id, 10);
            $this->cacheUserStats($user->id);
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats()
    {
        try {
            if (class_exists('Redis')) {
                $info = Redis::info();
                return [
                    'used_memory' => $info['used_memory_human'] ?? 'N/A',
                    'connected_clients' => $info['connected_clients'] ?? 'N/A',
                    'total_commands_processed' => $info['total_commands_processed'] ?? 'N/A',
                    'keyspace_hits' => $info['keyspace_hits'] ?? 'N/A',
                    'keyspace_misses' => $info['keyspace_misses'] ?? 'N/A',
                ];
            } else {
                return [
                    'cache_driver' => 'File Cache',
                    'status' => 'Active',
                    'fallback' => 'Using Laravel file cache'
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => 'Cache not available',
                'fallback' => 'Using file cache'
            ];
        }
    }

    /**
     * Clear all cache
     */
    public function clearAllCache()
    {
        try {
            if (class_exists('Redis')) {
                Redis::flushall();
            } else {
                Cache::flush();
            }
        } catch (\Exception $e) {
            Cache::flush();
        }
    }

    /**
     * Cache API responses
     */
    public function cacheApiResponse($key, $data, $duration = 60)
    {
        return Cache::put($key, $data, $duration);
    }

    /**
     * Get cached API response
     */
    public function getCachedApiResponse($key)
    {
        return Cache::get($key);
    }
}
