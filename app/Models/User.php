<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }
        // Use media route to avoid OS symlink issues on Windows hosting
        return url('/media/' . ltrim($this->avatar, '/'));
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'from_user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Takip ettiği kullanıcılar
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    // Takipçileri
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    // Bir kullanıcıyı takip ediyor mu?
    public function isFollowing($user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    // Takipçi sayısı
    public function followersCount()
    {
        return $this->followers()->count();
    }

    // Takip ettiği kişi sayısı
    public function followingCount()
    {
        return $this->following()->count();
    }

    /**
     * Get the user's stories
     */
    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    /**
     * Get the user's active stories
     */
    public function activeStories()
    {
        return $this->stories()->active();
    }

    /**
     * Get the user's story highlights
     */
    public function storyHighlights()
    {
        return $this->stories()->where('is_highlight', true);
    }

    /**
     * Get the user's story views
     */
    public function storyViews()
    {
        return $this->hasMany(StoryView::class);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'bio' => $this->bio,
            'is_verified' => $this->is_verified,
            'created_at' => $this->created_at->timestamp,
            'posts_count' => $this->posts()->count(),
            'followers_count' => $this->followers()->count(),
            'following_count' => $this->following()->count(),
        ];
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'users_index';
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return $this->exists;
    }
}
