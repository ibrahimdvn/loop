<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Konuşma listesi - her kullanıcıyla son mesajı getir
        $conversations = DB::table('messages')
            ->select('messages.*', 'users.name as other_user_name')
            ->join('users', function($join) {
                $join->on('users.id', '=', 'messages.sender_id')
                     ->orOn('users.id', '=', 'messages.receiver_id');
            })
            ->where(function($query) {
                $query->where('messages.sender_id', Auth::id())
                      ->orWhere('messages.receiver_id', Auth::id());
            })
            ->where('users.id', '!=', Auth::id())
            ->orderBy('messages.created_at', 'desc')
            ->get()
            ->groupBy(function($message) {
                // Konuşma partneri ID'sine göre grupla
                return $message->sender_id == Auth::id() 
                    ? $message->receiver_id 
                    : $message->sender_id;
            })
            ->map(function($messages) {
                return $messages->first(); // Her gruptan ilk (son) mesajı al
            });

        return view('messages.index', compact('conversations'));
    }

    public function show($userId)
    {
        $otherUser = User::findOrFail($userId);
        
        // İki kullanıcı arasındaki mesajları getir
        $messages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Gelen mesajları okundu olarak işaretle
        Message::where('sender_id', $userId)
               ->where('receiver_id', Auth::id())
               ->where('read', false)
               ->update(['read' => true]);

        return view('messages.show', compact('messages', 'otherUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        // Broadcast the message event
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function users()
    {
        // Mesaj gönderilebilecek kullanıcılar listesi
        $users = User::where('id', '!=', Auth::id())
                    ->select('id', 'name')
                    ->get();

        return view('messages.users', compact('users'));
    }
}
