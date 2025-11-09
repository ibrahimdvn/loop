<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Custom Auth Routes (without auto-login)
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\PostController::class, 'index'])->name('home');
    Route::post('/posts', [App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [App\Http\Controllers\PostController::class, 'toggleLike'])->name('posts.like');

    // Comments
    Route::post('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::get('/posts/{post}/comments', [App\Http\Controllers\CommentController::class, 'index'])->name('comments.index');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.delete-all');

    // Messages
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/users', [App\Http\Controllers\MessageController::class, 'users'])->name('messages.users');
    Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // Reels (Akış)
    Route::get('/reels', [App\Http\Controllers\ReelsController::class, 'index'])->name('reels.index');
    Route::get('/api/reels/load-more', [App\Http\Controllers\ReelsController::class, 'loadMore'])->name('reels.load-more');

    // Stories
    Route::get('/stories', [App\Http\Controllers\StoryController::class, 'index'])->name('stories.index');
    Route::get('/api/stories/active', [App\Http\Controllers\StoryController::class, 'getActiveStories'])->name('stories.active');
    Route::get('/stories/{user}', [App\Http\Controllers\StoryController::class, 'show'])->name('stories.show');
    Route::post('/stories', [App\Http\Controllers\StoryController::class, 'store'])->name('stories.store');
    Route::delete('/stories/{story}', [App\Http\Controllers\StoryController::class, 'destroy'])->name('stories.destroy');
    Route::get('/stories/{story}/viewers', [App\Http\Controllers\StoryController::class, 'viewers'])->name('stories.viewers');
    Route::post('/api/stories/{story}/like', [App\Http\Controllers\StoryController::class, 'like'])->name('stories.like');

    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [App\Http\Controllers\SettingsController::class, 'deleteAccount'])->name('settings.delete-account');

    // Profile
    Route::get('/profile/{id?}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [App\Http\Controllers\ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Follow System
    Route::post('/follow/{user}', [App\Http\Controllers\FollowController::class, 'follow'])->name('follow');
    Route::delete('/follow/{user}', [App\Http\Controllers\FollowController::class, 'unfollow'])->name('unfollow');
    Route::get('/profile/{user}/followers', [App\Http\Controllers\FollowController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user}/following', [App\Http\Controllers\FollowController::class, 'following'])->name('profile.following');

    // Search
    Route::get('/search/user/{username}', [App\Http\Controllers\SearchController::class, 'searchUser'])->name('search.user');
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
    Route::get('/api/search-suggestions', [App\Http\Controllers\SearchController::class, 'suggestions'])->name('search.suggestions');
    
    // Trending Routes
    Route::get('/trending', [App\Http\Controllers\TrendingController::class, 'getTrendingTopics'])->name('trending');
    Route::get('/api/trending/personalized', [App\Http\Controllers\TrendingController::class, 'getPersonalizedTrending'])->name('trending.personalized');
    Route::get('/api/trending/hashtags', [App\Http\Controllers\TrendingController::class, 'getTrendingHashtags'])->name('trending.hashtags');
    Route::get('/api/trending/posts', [App\Http\Controllers\TrendingController::class, 'getTrendingPosts'])->name('trending.posts');
    Route::get('/api/trending/statistics', [App\Http\Controllers\TrendingController::class, 'getTrendingStatistics'])->name('trending.statistics');
    Route::post('/api/trending/clear-cache', [App\Http\Controllers\TrendingController::class, 'clearTrendingCache'])->name('trending.clear-cache');

    // Analytics Routes
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/api/analytics/overview', [App\Http\Controllers\AnalyticsController::class, 'overview'])->name('analytics.overview');
    Route::get('/api/analytics/engagement', [App\Http\Controllers\AnalyticsController::class, 'engagement'])->name('analytics.engagement');
    Route::get('/api/analytics/content-performance', [App\Http\Controllers\AnalyticsController::class, 'contentPerformance'])->name('analytics.content-performance');
    Route::get('/api/analytics/user-growth', [App\Http\Controllers\AnalyticsController::class, 'userGrowth'])->name('analytics.user-growth');
    Route::get('/api/analytics/realtime', [App\Http\Controllers\AnalyticsController::class, 'realTime'])->name('analytics.realtime');
    Route::get('/api/analytics/user/{userId}', [App\Http\Controllers\AnalyticsController::class, 'userAnalytics'])->name('analytics.user');
    Route::post('/api/analytics/clear-cache', [App\Http\Controllers\AnalyticsController::class, 'clearCache'])->name('analytics.clear-cache');
});

// Media proxy (serve public disk files without relying on OS symlinks)
Route::get('/media/{path}', function ($path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }
    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('media');
