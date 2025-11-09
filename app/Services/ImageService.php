<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Check if GD is available
        if (extension_loaded('gd')) {
            $this->manager = new ImageManager(['driver' => 'gd']);
        } else {
            $this->manager = null; // Will use fallback methods
        }
    }

    /**
     * Process and optimize uploaded image
     *
     * @param UploadedFile $file
     * @param string $path
     * @param array $sizes
     * @return array
     */
    public function processImage(UploadedFile $file, string $path = 'images', array $sizes = [])
    {
        $filename = $this->generateFilename($file);
        $originalPath = $file->storeAs($path, $filename, 'public');
        
        $processedImages = [];
        
        // If GD is not available, just return original
        if (!$this->manager) {
            return [
                'original' => $originalPath,
                'processed' => [],
                'filename' => $filename
            ];
        }
        
        // Default sizes if none provided
        if (empty($sizes)) {
            $sizes = [
                'thumbnail' => [150, 150],
                'medium' => [400, 400],
                'large' => [800, 800],
            ];
        }

        try {
            foreach ($sizes as $sizeName => $dimensions) {
                $processedPath = $this->resizeAndOptimize($originalPath, $sizeName, $dimensions[0], $dimensions[1]);
                $processedImages[$sizeName] = $processedPath;
            }
        } catch (\Exception $e) {
            \Log::warning('Image processing failed: ' . $e->getMessage());
        }

        return [
            'original' => $originalPath,
            'processed' => $processedImages,
            'filename' => $filename
        ];
    }

    /**
     * Resize and optimize image
     *
     * @param string $imagePath
     * @param string $sizeName
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function resizeAndOptimize(string $imagePath, string $sizeName, int $width, int $height): string
    {
        // If GD is not available, return original
        if (!$this->manager) {
            return $imagePath;
        }

        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            $pathInfo = pathinfo($imagePath);
            
            $newFilename = $pathInfo['filename'] . '_' . $sizeName . '.' . $pathInfo['extension'];
            $newPath = $pathInfo['dirname'] . '/' . $newFilename;
            $newFullPath = Storage::disk('public')->path($newPath);

            // Create image instance
            $image = $this->manager->make($fullPath);
            
            // Resize with aspect ratio preservation
            $image->fit($width, $height);
            
            // Optimize quality
            $quality = $this->getOptimalQuality($sizeName);
            
            // Save optimized image
            $image->save($newFullPath, $quality);

            return $newPath;
        } catch (\Exception $e) {
            \Log::warning('Image resize failed: ' . $e->getMessage());
            return $imagePath;
        }
    }

    /**
     * Get optimal quality based on size
     *
     * @param string $sizeName
     * @return int
     */
    protected function getOptimalQuality(string $sizeName): int
    {
        return match($sizeName) {
            'thumbnail' => 70,
            'medium' => 80,
            'large' => 85,
            'original' => 90,
            default => 80
        };
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
     * Create avatar from uploaded image
     *
     * @param UploadedFile $file
     * @return string
     */
    public function createAvatar(UploadedFile $file): string
    {
        $result = $this->processImage($file, 'avatars', [
            'small' => [50, 50],
            'medium' => [100, 100],
            'large' => [200, 200]
        ]);

        // If no processed images (GD not available), use original
        if (empty($result['processed'])) {
            return $result['original'];
        }

        return $result['processed']['medium'] ?? $result['original'];
    }

    /**
     * Create post image with multiple sizes
     *
     * @param UploadedFile $file
     * @return array
     */
    public function createPostImage(UploadedFile $file): array
    {
        return $this->processImage($file, 'posts', [
            'thumbnail' => [300, 300],
            'medium' => [600, 600],
            'large' => [1200, 1200]
        ]);
    }

    /**
     * Delete image and all its variants
     *
     * @param string $imagePath
     * @param array $sizes
     * @return bool
     */
    public function deleteImage(string $imagePath, array $sizes = []): bool
    {
        try {
            // Delete original
            Storage::disk('public')->delete($imagePath);
            
            // Delete variants
            $pathInfo = pathinfo($imagePath);
            foreach ($sizes as $sizeName) {
                $variantPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $sizeName . '.' . $pathInfo['extension'];
                Storage::disk('public')->delete($variantPath);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get image URL with size
     *
     * @param string $imagePath
     * @param string $size
     * @return string
     */
    public function getImageUrl(string $imagePath, string $size = 'original'): string
    {
        if ($size === 'original') {
            return Storage::disk('public')->url($imagePath);
        }

        $pathInfo = pathinfo($imagePath);
        $sizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $size . '.' . $pathInfo['extension'];
        
        // Check if sized version exists, fallback to original
        if (Storage::disk('public')->exists($sizedPath)) {
            return Storage::disk('public')->url($sizedPath);
        }
        
        return Storage::disk('public')->url($imagePath);
    }

    /**
     * Add watermark to image
     *
     * @param string $imagePath
     * @param string $watermarkText
     * @return string
     */
    public function addWatermark(string $imagePath, string $watermarkText = 'Loop'): string
    {
        $fullPath = Storage::disk('public')->path($imagePath);
        $pathInfo = pathinfo($imagePath);
        
        $watermarkedFilename = $pathInfo['filename'] . '_watermarked.' . $pathInfo['extension'];
        $watermarkedPath = $pathInfo['dirname'] . '/' . $watermarkedFilename;
        $watermarkedFullPath = Storage::disk('public')->path($watermarkedPath);

        $image = $this->manager->make($fullPath);
        
        // Add watermark
        $image->text($watermarkText, $image->width() - 10, $image->height() - 10, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(24);
            $font->color('#ffffff');
            $font->align('right');
            $font->valign('bottom');
        });
        
        $image->save($watermarkedFullPath);

        return $watermarkedPath;
    }
}
