<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'hashtags',
        'image',
        'video',
        'video_url',
        'video_thumbnail',
        'video_duration',
        'media_type',
        'media_url',
        'media_data'
    ];

    protected $casts = [
        'media_data' => 'array',
        'hashtags' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get optimized image URL
     */
    public function getImageUrl($size = 'medium')
    {
        if (!$this->image) {
            return null;
        }

        // If we have processed images in media_data
        if ($this->media_data && isset($this->media_data[$size])) {
            return url('/media/' . $this->media_data[$size]);
        }

        // Fallback to original image
        return url('/media/' . $this->image);
    }

    /**
     * Get all available image sizes
     */
    public function getImageSizes()
    {
        if (!$this->image) {
            return [];
        }

        if ($this->media_data) {
            return array_keys($this->media_data);
        }

        return ['original'];
    }

    /**
     * Get optimized video URL
     */
    public function getVideoUrl($quality = 'medium')
    {
        // Check new video columns first
        if ($this->video) {
            return url('/media/' . ltrim($this->video, '/'));
        }
        
        if ($this->video_url) {
            return $this->video_url;
        }

        // Fallback to old media_url structure
        if (!$this->media_url || $this->media_type !== 'video') {
            return null;
        }

        // If we have processed videos in media_data
        if ($this->media_data && isset($this->media_data[$quality])) {
            return url('/media/' . ltrim($this->media_data[$quality], '/'));
        }

        // Fallback to original video
        return asset('storage/' . $this->media_url);
    }

    /**
     * Get video thumbnail URL
     */
    public function getVideoThumbnailUrl()
    {
        if (!$this->media_url || $this->media_type !== 'video') {
            return null;
        }

        $pathInfo = pathinfo($this->media_url);
        $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.jpg';
        
        if (\Storage::disk('public')->exists($thumbnailPath)) {
            return asset('storage/' . $thumbnailPath);
        }

        return asset('images/video-placeholder.jpg');
    }

    /**
     * Get all available video qualities
     */
    public function getVideoQualities()
    {
        if (!$this->media_url || $this->media_type !== 'video') {
            return [];
        }

        if ($this->media_data) {
            return array_keys($this->media_data);
        }

        return ['original'];
    }

    /**
     * Check if post has video
     */
    public function hasVideo()
    {
        return $this->media_type === 'video' && $this->media_url;
    }

    /**
     * Check if post has image
     */
    public function hasImage()
    {
        return $this->media_type === 'image' && $this->image;
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
            'title' => $this->title,
            'content' => $this->content,
            'hashtags' => $this->hashtags,
            'user_name' => $this->user->name ?? '',
            'user_email' => $this->user->email ?? '',
            'created_at' => $this->created_at->timestamp,
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),
            'media_type' => $this->media_type,
        ];
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'posts_index';
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
