<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function searchUser($username)
    {
        try {
            // Kullanıcı adını temizle ve arama yap
            $cleanUsername = strtolower(trim($username));
            
            // Kullanıcıyı bul (kullanıcı adı veya isim ile)
            $user = User::where(function($query) use ($cleanUsername) {
                // Tam eşleşme: "ibrahimcan" -> "İbrahim Can"
                $query->whereRaw('LOWER(REPLACE(name, " ", "")) = ?', [$cleanUsername])
                      // Kısmi eşleşme: "ibrahim" -> "İbrahim Can"
                      ->orWhereRaw('LOWER(REPLACE(name, " ", "")) LIKE ?', ["%{$cleanUsername}%"])
                      // İsim ile arama: "İbrahim" -> "İbrahim Can"
                      ->orWhereRaw('LOWER(name) LIKE ?', ["%{$cleanUsername}%"]);
            })->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kullanıcı bulunamadı'
                ]);
            }

            // Kullanıcı istatistiklerini hesapla
            $postsCount = $user->posts()->count();
            $followersCount = $user->followers()->count();

            // Kullanıcı adını oluştur (isimden)
            $generatedUsername = strtolower(str_replace(' ', '', $user->name));

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $generatedUsername,
                    'avatar' => $user->avatar,
                    'bio' => $user->bio,
                    'is_verified' => $user->is_verified,
                    'posts_count' => $postsCount,
                    'followers_count' => $followersCount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arama sırasında hata oluştu'
            ], 500);
        }
    }

    /**
     * Advanced search using Laravel Scout
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, users, posts, hashtags
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Arama terimi gerekli'
            ], 400);
        }

        $results = [];

        try {
            switch ($type) {
                case 'users':
                    $results = $this->searchUsers($query, $page, $perPage);
                    break;
                case 'posts':
                    $results = $this->searchPosts($query, $page, $perPage);
                    break;
                case 'hashtags':
                    $results = $this->searchHashtags($query, $page, $perPage);
                    break;
                default:
                    $results = $this->searchAll($query, $page, $perPage);
            }

            return response()->json([
                'success' => true,
                'query' => $query,
                'type' => $type,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arama sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users using Scout
     */
    protected function searchUsers($query, $page, $perPage)
    {
        $users = User::search($query)
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->paginate($perPage, 'page', $page);

        return [
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'has_more' => $users->hasMorePages()
            ]
        ];
    }

    /**
     * Search posts using Scout
     */
    protected function searchPosts($query, $page, $perPage)
    {
        $posts = Post::search($query)
            ->with(['user', 'likes', 'comments'])
            ->paginate($perPage, 'page', $page);

        return [
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'has_more' => $posts->hasMorePages()
            ]
        ];
    }

    /**
     * Search hashtags
     */
    protected function searchHashtags($query, $page, $perPage)
    {
        $hashtag = ltrim($query, '#');
        
        $posts = Post::whereJsonContains('hashtags', $hashtag)
            ->with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate($perPage, 'page', $page);

        return [
            'hashtag' => $hashtag,
            'posts' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'has_more' => $posts->hasMorePages()
            ]
        ];
    }

    /**
     * Search all content types
     */
    protected function searchAll($query, $page, $perPage)
    {
        $users = User::search($query)
            ->where('id', '!=', Auth::id())
            ->limit(5)
            ->get();

        $posts = Post::search($query)
            ->with(['user', 'likes', 'comments'])
            ->limit($perPage)
            ->get();

        $hashtags = $this->getTrendingHashtags($query);

        return [
            'users' => $users,
            'posts' => $posts,
            'hashtags' => $hashtags,
            'summary' => [
                'users_count' => $users->count(),
                'posts_count' => $posts->count(),
                'hashtags_count' => count($hashtags)
            ]
        ];
    }

    /**
     * Get trending hashtags related to search query
     */
    protected function getTrendingHashtags($query)
    {
        $hashtag = ltrim($query, '#');
        
        return Post::whereJsonContains('hashtags', $hashtag)
            ->get()
            ->pluck('hashtags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(10)
            ->keys()
            ->toArray();
    }

    /**
     * Get search suggestions
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'suggestions' => []
            ]);
        }

        try {
            // User suggestions
            $userSuggestions = User::search($query)
                ->where('id', '!=', Auth::id())
                ->limit(5)
                ->get()
                ->map(function ($user) {
                    return [
                        'type' => 'user',
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar_url,
                        'is_verified' => $user->is_verified
                    ];
                });

            // Hashtag suggestions
            $hashtagSuggestions = Post::whereJsonContains('hashtags', ltrim($query, '#'))
                ->get()
                ->pluck('hashtags')
                ->flatten()
                ->filter()
                ->unique()
                ->take(5)
                ->map(function ($hashtag) {
                    return [
                        'type' => 'hashtag',
                        'name' => '#' . $hashtag
                    ];
                });

            $suggestions = $userSuggestions->concat($hashtagSuggestions)->take(10);

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Öneriler alınırken hata oluştu'
            ], 500);
        }
    }
}
