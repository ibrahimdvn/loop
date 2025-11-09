<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReelsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Video içeren gönderileri getir
        $reels = Post::where(function($query) {
                $query->whereNotNull('video')
                      ->orWhereNotNull('video_url');
            })
            ->with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('reels.index', compact('reels'));
    }

    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        
        $reels = Post::where(function($query) {
                $query->whereNotNull('video')
                      ->orWhereNotNull('video_url');
            })
            ->with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'reels' => $reels->items(),
            'hasMore' => $reels->hasMorePages(),
            'nextPage' => $reels->currentPage() + 1
        ]);
    }
}

