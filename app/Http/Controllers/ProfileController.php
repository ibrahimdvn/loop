<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->middleware('auth');
        $this->imageService = $imageService;
    }

    public function show($id = null)
    {
        // Eğer ID verilmemişse, kendi profilini göster
        $user = $id ? \App\Models\User::findOrFail($id) : Auth::user();
        
        // Kullanıcının gönderilerini al (video olanları hariç tut - reels'e ayrıldı)
        $posts = $user->posts()
            ->where(function($query) {
                $query->whereNull('video')
                      ->whereNull('video_url');
            })
            ->with('user')
            ->latest()
            ->paginate(10);
        
        // Kendi profili mi?
        $isOwnProfile = Auth::id() === $user->id;
        
        // İstatistikler
        $stats = [
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followersCount(),
            'following_count' => $user->followingCount(),
        ];
        
        // Takip durumu
        $isFollowing = Auth::check() && Auth::user()->isFollowing($user);
        
        return view('profile.show', compact('user', 'posts', 'isOwnProfile', 'stats', 'isFollowing'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Eski avatar'ı sil
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Yeni avatar'ı optimize et ve kaydet
        $avatarPath = $this->imageService->createAvatar($request->file('avatar'));
        
        $user->update([
            'avatar' => $avatarPath
        ]);

        return back()->with('success', 'Profil resminiz güncellendi!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return back()->with('success', 'Profil resminiz kaldırıldı!');
    }
}
