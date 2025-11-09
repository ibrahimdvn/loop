<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->middleware('auth');
        $this->cacheService = $cacheService;
    }

    public function follow(User $user)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->id === $user->id) {
            return response()->json(['error' => 'Kendinizi takip edemezsiniz'], 400);
        }
        
        if ($currentUser->isFollowing($user)) {
            return response()->json(['error' => 'Zaten takip ediyorsunuz'], 400);
        }
        
        $currentUser->following()->attach($user->id);
        
        // Invalidate cache for both users
        $this->cacheService->invalidateUserCache($currentUser->id);
        $this->cacheService->invalidateUserCache($user->id);
        
        return response()->json([
            'success' => true,
            'message' => $user->name . ' kullanıcısını takip etmeye başladınız',
            'followers_count' => $user->followersCount()
        ]);
    }
    
    public function unfollow(User $user)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->isFollowing($user)) {
            return response()->json(['error' => 'Zaten takip etmiyorsunuz'], 400);
        }
        
        $currentUser->following()->detach($user->id);
        
        // Invalidate cache for both users
        $this->cacheService->invalidateUserCache($currentUser->id);
        $this->cacheService->invalidateUserCache($user->id);
        
        return response()->json([
            'success' => true,
            'message' => $user->name . ' kullanıcısını takip etmeyi bıraktınız',
            'followers_count' => $user->followersCount()
        ]);
    }
    
    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);
        
        return view('profile.followers', compact('user', 'followers'));
    }
    
    public function following(User $user)
    {
        $following = $user->following()->paginate(20);
        
        return view('profile.following', compact('user', 'following'));
    }
}