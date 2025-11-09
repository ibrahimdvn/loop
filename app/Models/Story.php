<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'media_type',
        'media_url',
        'media_path',
        'media_data',
        'duration',
        'caption',
        'hashtags',
        'mentions',
        'background_color',
        'text_color',
        'font_family',
        'font_size',
        'is_highlight',
        'highlight_title',
        'highlight_cover',
        'expires_at',
    ];

    protected $casts = [
        'media_data' => 'array',
        'hashtags' => 'array',
        'mentions' => 'array',
        'expires_at' => 'datetime',
        'is_highlight' => 'boolean',
    ];

    /**
     * Get the user that owns the story
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the story views
     */
    public function views()
    {
        return $this->hasMany(StoryView::class);
    }

    /**
     * Get the story viewers
     */
    public function viewers()
    {
        return $this->belongsToMany(User::class, 'story_views')
            ->withPivot('viewed_at')
            ->withTimestamps();
    }

    /**
     * Get the story likes
     */
    public function likes()
    {
        return $this->hasMany(StoryLike::class);
    }

    /**
     * Check if user has liked this story
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Check if story is expired
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if story is active (not expired)
     */
    public function isActive()
    {
        return !$this->isExpired();
    }

    /**
     * Get story media URL
     */
    public function getMediaUrl()
    {
        if ($this->media_path) {
            return url('/media/' . ltrim($this->media_path, '/'));
        }
        
        if ($this->media_url) {
            return $this->media_url;
        }
        
        return null;
    }

    /**
     * Get story thumbnail URL
     */
    public function getThumbnailUrl()
    {
        if ($this->media_type === 'video' && $this->media_data && isset($this->media_data['thumbnail'])) {
            return Storage::disk('public')->url($this->media_data['thumbnail']);
        }
        
        return $this->media_url;
    }

    /**
     * Check if user has viewed this story
     */
    public function isViewedBy($userId)
    {
        return $this->views()->where('user_id', $userId)->exists();
    }

    /**
     * Get view count
     */
    public function getViewCountAttribute()
    {
        return $this->views()->count();
    }

    /**
     * Get time remaining until expiration
     */
    public function getTimeRemainingAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return $this->expires_at->diffInSeconds(now());
    }

    /**
     * Get formatted time remaining
     */
    public function getFormattedTimeRemainingAttribute()
    {
        if ($this->isExpired()) {
            return 'Expired';
        }
        
        $hours = $this->expires_at->diffInHours(now());
        $minutes = $this->expires_at->diffInMinutes(now()) % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    /**
     * Scope for active stories
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for expired stories
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for stories by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for stories from followed users
     */
    public function scopeFromFollowedUsers($query, $userId)
    {
        return $query->whereHas('user.followers', function ($q) use ($userId) {
            $q->where('follower_id', $userId);
        })->orWhere('user_id', $userId); // Include own stories
    }

    /**
     * Create a new story with automatic expiration
     */
    public static function createWithExpiration(array $data)
    {
        $data['expires_at'] = now()->addHours(24);
        return static::create($data);
    }

    /**
     * Extend story expiration (for highlights)
     */
    public function extendExpiration($hours = 24)
    {
        $this->update(['expires_at' => now()->addHours($hours)]);
    }

    /**
     * Convert to highlight
     */
    public function convertToHighlight($title, $cover = null)
    {
        $this->update([
            'is_highlight' => true,
            'highlight_title' => $title,
            'highlight_cover' => $cover,
            'expires_at' => now()->addDays(365), // Highlights last 1 year
        ]);
    }

    /**
     * Remove from highlights
     */
    public function removeFromHighlights()
    {
        $this->update([
            'is_highlight' => false,
            'highlight_title' => null,
            'highlight_cover' => null,
            'expires_at' => now()->addHours(24), // Back to 24 hours
        ]);
    }
}
