<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;

class VideoService
{
    /**
     * Process and optimize uploaded video
     *
     * @param UploadedFile $file
     * @param string $path
     * @return array
     */
    public function processVideo(UploadedFile $file, string $path = 'videos')
    {
        try {
            $filename = $this->generateFilename($file);
            $originalPath = $file->storeAs($path, $filename, 'public');
            $fullPath = Storage::disk('public')->path($originalPath);
            
            $processedVideos = [];
            
            // Generate thumbnail
            $thumbnailPath = $this->generateThumbnail($fullPath, $path, $filename);
            
            // Create different quality versions
            $qualities = [
                'low' => ['width' => 480, 'height' => 360, 'bitrate' => '500k'],
                'medium' => ['width' => 720, 'height' => 480, 'bitrate' => '1000k'],
                'high' => ['width' => 1280, 'height' => 720, 'bitrate' => '2000k'],
            ];
            
            foreach ($qualities as $quality => $config) {
                $processedPath = $this->convertVideo($fullPath, $path, $filename, $quality, $config);
                if ($processedPath) {
                    $processedVideos[$quality] = $processedPath;
                }
            }
            
            return [
                'original' => $originalPath,
                'processed' => $processedVideos,
                'thumbnail' => $thumbnailPath,
                'filename' => $filename,
                'duration' => $this->getVideoDuration($fullPath),
                'size' => $file->getSize(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Video processing failed: ' . $e->getMessage());
            throw new \Exception('Video işleme başarısız: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate video thumbnail
     *
     * @param string $videoPath
     * @param string $path
     * @param string $filename
     * @return string
     */
    protected function generateThumbnail(string $videoPath, string $path, string $filename): string
    {
        try {
            $pathInfo = pathinfo($filename);
            $thumbnailFilename = $pathInfo['filename'] . '_thumb.jpg';
            $thumbnailPath = $path . '/' . $thumbnailFilename;
            $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);
            
            FFMpeg::fromDisk('public')
                ->open($path . '/' . $filename)
                ->getFrameFromTimecode(TimeCode::fromSeconds(1))
                ->export()
                ->toDisk('public')
                ->save($thumbnailPath);
                
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Convert video to different quality
     *
     * @param string $videoPath
     * @param string $path
     * @param string $filename
     * @param string $quality
     * @param array $config
     * @return string|null
     */
    protected function convertVideo(string $videoPath, string $path, string $filename, string $quality, array $config): ?string
    {
        try {
            $pathInfo = pathinfo($filename);
            $convertedFilename = $pathInfo['filename'] . '_' . $quality . '.mp4';
            $convertedPath = $path . '/' . $convertedFilename;
            
            $format = (new X264('aac', 'libx264'))
                ->setKiloBitrate($config['bitrate'])
                ->setAdditionalParameters(['-preset', 'fast']);
            
            FFMpeg::fromDisk('public')
                ->open($path . '/' . $filename)
                ->addFilter(function ($filters) use ($config) {
                    $filters->resize(new \FFMpeg\Coordinate\Dimension($config['width'], $config['height']));
                })
                ->export()
                ->toDisk('public')
                ->inFormat($format)
                ->save($convertedPath);
                
            return $convertedPath;
            
        } catch (\Exception $e) {
            Log::error("Video conversion failed for quality {$quality}: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get video duration
     *
     * @param string $videoPath
     * @return int
     */
    protected function getVideoDuration(string $videoPath): int
    {
        try {
            $media = FFMpeg::open($videoPath);
            return $media->getDurationInSeconds();
        } catch (\Exception $e) {
            Log::error('Duration detection failed: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Generate unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        
        return $filename;
    }
    
    /**
     * Create post video with multiple qualities
     *
     * @param UploadedFile $file
     * @return array
     */
    public function createPostVideo(UploadedFile $file): array
    {
        return $this->processVideo($file, 'videos/posts');
    }
    
    /**
     * Create story video (shorter, optimized for mobile)
     *
     * @param UploadedFile $file
     * @return array
     */
    public function createStoryVideo(UploadedFile $file): array
    {
        try {
            $filename = $this->generateFilename($file);
            $originalPath = $file->storeAs('videos/stories', $filename, 'public');
            $fullPath = Storage::disk('public')->path($originalPath);
            
            // Story videos are typically shorter and mobile-optimized
            $storyQualities = [
                'mobile' => ['width' => 720, 'height' => 1280, 'bitrate' => '1500k'], // Vertical
                'web' => ['width' => 1080, 'height' => 1920, 'bitrate' => '2500k'], // High quality vertical
            ];
            
            $processedVideos = [];
            $thumbnailPath = $this->generateThumbnail($fullPath, 'videos/stories', $filename);
            
            foreach ($storyQualities as $quality => $config) {
                $processedPath = $this->convertVideo($fullPath, 'videos/stories', $filename, $quality, $config);
                if ($processedPath) {
                    $processedVideos[$quality] = $processedPath;
                }
            }
            
            return [
                'original' => $originalPath,
                'processed' => $processedVideos,
                'thumbnail' => $thumbnailPath,
                'filename' => $filename,
                'duration' => $this->getVideoDuration($fullPath),
                'size' => $file->getSize(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Story video processing failed: ' . $e->getMessage());
            throw new \Exception('Story video işleme başarısız: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete video and all its variants
     *
     * @param string $videoPath
     * @param array $qualities
     * @return bool
     */
    public function deleteVideo(string $videoPath, array $qualities = []): bool
    {
        try {
            // Delete original
            Storage::disk('public')->delete($videoPath);
            
            // Delete variants
            $pathInfo = pathinfo($videoPath);
            foreach ($qualities as $quality) {
                $variantPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $quality . '.mp4';
                Storage::disk('public')->delete($variantPath);
            }
            
            // Delete thumbnail
            $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.jpg';
            Storage::disk('public')->delete($thumbnailPath);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Video deletion failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get video URL with quality
     *
     * @param string $videoPath
     * @param string $quality
     * @return string
     */
    public function getVideoUrl(string $videoPath, string $quality = 'medium'): string
    {
        if ($quality === 'original') {
            return Storage::disk('public')->url($videoPath);
        }
        
        $pathInfo = pathinfo($videoPath);
        $qualityPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $quality . '.mp4';
        
        // Check if quality version exists, fallback to original
        if (Storage::disk('public')->exists($qualityPath)) {
            return Storage::disk('public')->url($qualityPath);
        }
        
        return Storage::disk('public')->url($videoPath);
    }
    
    /**
     * Get video thumbnail URL
     *
     * @param string $videoPath
     * @return string
     */
    public function getThumbnailUrl(string $videoPath): string
    {
        $pathInfo = pathinfo($videoPath);
        $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.jpg';
        
        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }
        
        return asset('images/video-placeholder.jpg'); // Fallback image
    }
    
    /**
     * Validate video file
     *
     * @param UploadedFile $file
     * @return array
     */
    public function validateVideo(UploadedFile $file): array
    {
        $errors = [];
        
        // Check file size (max 100MB)
        if ($file->getSize() > 100 * 1024 * 1024) {
            $errors[] = 'Video dosyası çok büyük. Maksimum 100MB olmalıdır.';
        }
        
        // Check file type
        $allowedTypes = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = 'Desteklenmeyen video formatı. Desteklenen formatlar: ' . implode(', ', $allowedTypes);
        }
        
        // Check MIME type
        $allowedMimes = [
            'video/mp4',
            'video/avi',
            'video/quicktime',
            'video/x-ms-wmv',
            'video/x-flv',
            'video/webm'
        ];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Geçersiz video dosyası.';
        }
        
        return $errors;
    }
    
    /**
     * Get video metadata
     *
     * @param string $videoPath
     * @return array
     */
    public function getVideoMetadata(string $videoPath): array
    {
        try {
            $fullPath = Storage::disk('public')->path($videoPath);
            $media = FFMpeg::open($videoPath);
            
            return [
                'duration' => $media->getDurationInSeconds(),
                'width' => $media->getVideoStream()->get('width'),
                'height' => $media->getVideoStream()->get('height'),
                'fps' => $media->getVideoStream()->get('r_frame_rate'),
                'bitrate' => $media->getVideoStream()->get('bit_rate'),
                'size' => Storage::disk('public')->size($videoPath),
            ];
        } catch (\Exception $e) {
            Log::error('Video metadata extraction failed: ' . $e->getMessage());
            return [];
        }
    }
}
