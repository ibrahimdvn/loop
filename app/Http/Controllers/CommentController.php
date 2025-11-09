<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->middleware('auth');
        $this->cacheService = $cacheService;
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|max:1000'
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        $comment->load('user');

        // Create notification (even if it's your own post)
        \App\Models\Notification::create([
            'user_id' => $post->user_id,
            'notifiable_id' => Auth::id(),
            'type' => 'comment',
            'post_id' => $post->id,
            'comment_id' => $comment->id,
            'message' => Auth::user()->name . ' gönderinize yorum yaptı: ' . substr($request->content, 0, 50)
        ]);

        // Invalidate cache for post and user
        $this->cacheService->invalidatePostCache($post->id);
        $this->cacheService->invalidateUserCache(Auth::id());
        $this->cacheService->invalidateUserCache($post->user_id);

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'comment_count' => $post->comments()->count(),
            'post_id' => $post->id
        ]);
    }

    public function index(Post $post)
    {
        $comments = $post->comments()->with('user')->latest()->get();
        
        return response()->json([
            'comments' => $comments,
            'comment_count' => $comments->count()
        ]);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $postId = $comment->post_id;
        $post = $comment->post;
        
        // Delete related notification
        \App\Models\Notification::where('comment_id', $comment->id)
            ->where('type', 'comment')
            ->delete();
        
        $comment->delete();

        // Invalidate cache for post and users
        $this->cacheService->invalidatePostCache($postId);
        $this->cacheService->invalidateUserCache(Auth::id());
        if ($post) {
            $this->cacheService->invalidateUserCache($post->user_id);
        }

        $commentCount = Comment::where('post_id', $postId)->count();

        return response()->json([
            'success' => true,
            'comments_count' => $commentCount,
            'post_id' => $postId
        ]);
    }
}