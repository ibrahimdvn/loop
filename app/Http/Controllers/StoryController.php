<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryView;
use App\Models\StoryLike;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get all active stories from followed users
     */
    public function index()
    {
        $followingIds = Auth::user()->following()->pluck('following_id')->toArray();
        $followingIds[] = Auth::id(); // Include own stories

        // Get users with active stories
        $usersWithStories = User::whereIn('id', $followingIds)
            ->whereHas('activeStories')
            ->with(['activeStories' => function($query) {
                $query->where('expires_at', '>', now())
                      ->orderBy('created_at', 'asc');
            }])
            ->get();

        return view('stories.index', compact('usersWithStories'));
    }

    /**
     * Get active stories for API (used in home page)
     */
    public function getActiveStories()
    {
        $followingIds = Auth::user()->following()->pluck('following_id')->toArray();
        $followingIds[] = Auth::id();

        $users = User::whereIn('id', $followingIds)
            ->whereHas('activeStories')
            ->with(['activeStories' => function($query) {
                $query->where('expires_at', '>', now())
                      ->orderBy('created_at', 'asc');
            }])
            ->get()
            ->map(function($user) {
                $hasViewed = $user->activeStories->every(function($story) {
                    return $story->isViewedBy(Auth::id());
                });
                
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'viewed' => $hasViewed,
                    'story_count' => $user->activeStories->count()
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Show specific user's stories
     */
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        $stories = $user->activeStories()->get();

        if ($stories->isEmpty()) {
            return redirect()->route('home')->with('error', 'Bu kullanıcının aktif hikayesi yok');
        }

        // Mark stories as viewed
        foreach ($stories as $story) {
            StoryView::firstOrCreate([
                'story_id' => $story->id,
                'user_id' => Auth::id()
            ]);
        }

        // Get liked story IDs for current user
        $likedStoryIds = StoryLike::where('user_id', Auth::id())
            ->whereIn('story_id', $stories->pluck('id'))
            ->pluck('story_id')
            ->toArray();

        return view('stories.show', compact('user', 'stories', 'likedStoryIds'));
    }

    /**
     * Store a new story
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480', // 20MB
                'type' => 'required|in:image,video'
            ]);

            $file = $request->file('media');
            $mediaPath = $file->store('stories', 'public');
            $mediaType = $request->type;
            $expiresAt = now()->addHours(24);

            // Determine duration
            $duration = 5; // default for images
            if ($mediaType === 'video') {
                $duration = 15; // default for videos
            }

            $story = Story::create([
                'user_id' => Auth::id(),
                'media_path' => $mediaPath,
                'media_url' => $mediaPath,
                'media_type' => $mediaType,
                'duration' => $duration,
                'expires_at' => $expiresAt
            ]);

            return response()->json([
                'success' => true,
                'story' => $story,
                'message' => 'Hikaye paylaşıldı!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hikaye yüklenemedi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a story
     */
    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok'], 403);
        }

        // Delete media file
        if (Storage::disk('public')->exists($story->media_path)) {
            Storage::disk('public')->delete($story->media_path);
        }

        $story->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hikaye silindi'
        ]);
    }

    /**
     * Get story viewers
     */
    public function viewers(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok'], 403);
        }

        $viewers = $story->views()
            ->with('user')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'viewers' => $viewers,
            'total_views' => $viewers->count()
        ]);
    }

    /**
     * Like a story (creates notification)
     */
    public function like(Story $story)
    {
        // Don't allow liking own story
        if ($story->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Kendi hikayeleri beğenemezsiniz'
            ], 400);
        }

        // Check if already liked
        $existingLike = StoryLike::where('story_id', $story->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            return response()->json([
                'success' => false,
                'message' => 'Hikaye zaten beğenilmiş'
            ], 400);
        }

        // Save like to database
        StoryLike::create([
            'story_id' => $story->id,
            'user_id' => Auth::id()
        ]);

        // Create notification
        \App\Models\Notification::create([
            'user_id' => $story->user_id,
            'notifiable_id' => Auth::id(),
            'type' => 'story_like',
            'message' => Auth::user()->name . ' hikayeni beğendi',
            'post_id' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hikaye beğenildi'
        ]);
    }
}
