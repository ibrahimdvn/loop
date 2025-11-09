<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->with(['notifiable', 'post', 'comment'])
            ->latest()
            ->paginate(20);

        // Okunmamış bildirimleri okundu olarak işaretle
        Auth::user()->notifications()->where('read', false)->update(['read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->where('read', false)->count();
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('read', false)->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json(['success' => true]);
    }

    // Bildirim oluşturma helper metodu
    public static function create($userId, $fromUserId, $postId, $type, $message)
    {
        return Notification::create([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'post_id' => $postId,
            'type' => $type,
            'message' => $message
        ]);
    }
}
