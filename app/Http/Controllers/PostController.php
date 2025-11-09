<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\ImageService;
use App\Services\CacheService;
use App\Services\VideoService;
use App\Services\TrendingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $imageService;
    protected $cacheService;
    protected $videoService;
    protected $trendingService;

    public function __construct(ImageService $imageService, CacheService $cacheService, VideoService $videoService, TrendingService $trendingService)
    {
        $this->middleware('auth');
        $this->imageService = $imageService;
        $this->cacheService = $cacheService;
        $this->videoService = $videoService;
        $this->trendingService = $trendingService;
    }

    public function index(Request $request)
    {
        // Use cache for better performance
        $posts = $this->cacheService->cacheHomeFeed(Auth::id(), $request->get('page', 1), 10);
        
        // Hashtag filtresi
        if ($request->has('hashtag')) {
            $hashtag = $request->get('hashtag');
            $posts = Post::with(['user', 'likes', 'comments'])
                ->whereJsonContains('hashtags', $hashtag)
                ->where(function($query) {
                    // Exclude video posts (reels)
                    $query->whereNull('video')
                          ->whereNull('video_url');
                })
                ->latest()
                ->paginate(10);
        }
        
        $suggestedUsers = $this->cacheService->cacheUserSuggestions(Auth::id(), 5);
        $trendingTopics = $this->cacheService->cacheTrendingHashtags(10);
        
        return view('home', compact('posts', 'suggestedUsers', 'trendingTopics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
            'media_type' => 'nullable|in:image,video,gif,location',
            'media_url' => 'nullable|string',
            'media_data' => 'nullable'
        ]);

        $imagePath = null;
        $mediaType = null;
        $mediaUrl = null;
        $mediaData = null;

        // Fotoğraf yükleme ve optimizasyon
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $imagePath = $path;
            $mediaType = 'image';
            $mediaUrl = $path;
        }

        // Video yükleme ve optimizasyon
        if ($request->hasFile('video')) {
            $videoErrors = $this->videoService->validateVideo($request->file('video'));
            if (!empty($videoErrors)) {
                return back()->withErrors(['video' => $videoErrors])->withInput();
            }

            $videoResult = $this->videoService->createPostVideo($request->file('video'));
            $mediaType = 'video';
            $mediaUrl = $videoResult['original'];
            $mediaData = json_encode([
                'processed' => $videoResult['processed'],
                'thumbnail' => $videoResult['thumbnail'],
                'duration' => $videoResult['duration'],
                'size' => $videoResult['size']
            ]);
        }

        // GIF veya konum verisi
        if ($request->media_type) {
            $mediaType = $request->media_type;
            $mediaUrl = $request->media_url;
            $mediaData = $request->media_data;
        }

        // Hashtag'leri çıkar
        $hashtags = $this->extractHashtags($request->content);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'hashtags' => $hashtags,
            'image' => $imagePath,
            'media_type' => $mediaType,
            'media_url' => $mediaUrl,
            'media_data' => $mediaData
        ]);

        // Invalidate cache after creating new post
        $this->cacheService->invalidateUserCache(Auth::id());
        $this->cacheService->invalidatePostCache($post->id);
        
        // Clear trending cache if post has hashtags
        if (!empty($hashtags)) {
            $this->trendingService->clearCache();
        }

        return redirect()->route('home');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok'], 403);
        }

        try {
            // Delete media files
            if ($post->image) {
                \Storage::disk('public')->delete($post->image);
            }
            if ($post->media_url) {
                \Storage::disk('public')->delete($post->media_url);
            }

            // Delete post
            $post->delete();

            // Clear cache
            $this->cacheService->invalidatePostCache($post->id);
            $this->cacheService->invalidateUserCache(Auth::id());
            
            // If from AJAX request, return JSON
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => 'Gönderi silindi']);
            }

            return redirect()->route('home')->with('success', 'Yazınız başarıyla silindi!');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Silme işlemi başarısız'], 500);
            }
            return back()->withErrors(['error' => 'Gönderi silinemedi']);
        }
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();
        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        
        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            
            // Remove notification if exists
            \App\Models\Notification::where('user_id', $post->user_id)
                ->where('notifiable_id', $user->id)
                ->where('type', 'like')
                ->where('post_id', $post->id)
                ->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
            
            // Create notification (even if it's your own post)
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'notifiable_id' => $user->id,
                'type' => 'like',
                'post_id' => $post->id,
                'message' => $user->name . ' gönderinizi beğendi'
            ]);
        }
        
        // Invalidate cache for post and users
        $this->cacheService->invalidatePostCache($post->id);
        $this->cacheService->invalidateUserCache(Auth::id());
        $this->cacheService->invalidateUserCache($post->user_id);
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }

    private function getSuggestedUsers()
    {
        $currentUserId = Auth::id();
        
        // Kullanıcının takip ettiği kişileri al
        $followingIds = \App\Models\User::find($currentUserId)->following()->pluck('following_id')->toArray();
        
        // Takip etmediği, kendisi olmayan kullanıcıları al
        $suggestedUsers = \App\Models\User::where('id', '!=', $currentUserId)
            ->whereNotIn('id', $followingIds)
            ->withCount(['posts', 'followers'])
            ->orderBy('followers_count', 'desc')
            ->orderBy('posts_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Eğer yeterli kullanıcı yoksa, en aktif kullanıcıları öner
        if ($suggestedUsers->count() < 3) {
            $additionalUsers = \App\Models\User::where('id', '!=', $currentUserId)
                ->whereNotIn('id', $followingIds)
                ->withCount(['posts', 'followers'])
                ->orderBy('posts_count', 'desc')
                ->limit(5 - $suggestedUsers->count())
                ->get();
                
            $suggestedUsers = $suggestedUsers->merge($additionalUsers);
        }
        
        return $suggestedUsers;
    }

    private function extractHashtags($content)
    {
        preg_match_all('/#(\w+)/', $content, $matches);
        return array_unique($matches[1]);
    }

    private function getTrendingTopics()
    {
        // Gündem konuları ve hashtag'leri
        $trendingTopics = [
            ['category' => 'Teknoloji', 'title' => 'Yapay Zeka', 'hashtag' => 'YapayZeka', 'trend' => '🚀'],
            ['category' => 'Tasarım', 'title' => 'UI/UX Tasarım', 'hashtag' => 'UIUX', 'trend' => '✨'],
            ['category' => 'Programlama', 'title' => 'Laravel', 'hashtag' => 'Laravel', 'trend' => '💻'],
            ['category' => 'Genel', 'title' => 'Sosyal Medya', 'hashtag' => 'SosyalMedya', 'trend' => '💬'],
            ['category' => 'İş', 'title' => 'Uzaktan Çalışma', 'hashtag' => 'UzaktanCalisma', 'trend' => '💼'],
        ];

        // Her hashtag için gerçek gönderi sayısını al
        foreach ($trendingTopics as &$topic) {
            $count = Post::whereJsonContains('hashtags', $topic['hashtag'])->count();
            $topic['posts'] = $count . ' gönderi';
        }

        return $trendingTopics;
    }
}
