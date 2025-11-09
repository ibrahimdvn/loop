<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('settings.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . Auth::id() . '|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'bio' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'email' => $request->email,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'Profil bilgileriniz güncellendi!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mevcut şifreniz yanlış.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Şifreniz güncellendi!');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()->withErrors(['password' => 'Şifreniz yanlış.']);
        }

        $user = Auth::user();
        
        // Kullanıcının gönderilerini sil
        $user->posts()->delete();
        
        // Kullanıcının bildirimlerini sil
        $user->notifications()->delete();
        $user->sentNotifications()->delete();
        
        // Kullanıcının mesajlarını sil
        $user->sentMessages()->delete();
        $user->receivedMessages()->delete();
        
        // Kullanıcıyı sil
        $user->delete();

        return redirect()->route('welcome')->with('success', 'Hesabınız başarıyla silindi.');
    }
}
