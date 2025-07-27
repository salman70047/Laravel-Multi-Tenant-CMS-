<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait HandlesFileUploads
{
    /**
     * Handle file upload and store it
     */
    public function handleFileUpload(UploadedFile $file, string $directory = 'uploads'): array
    {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs($directory, $filename, 'public');
        
        return [
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'url' => asset('storage/' . $path),
        ];
    }

    /**
     * Validate image file
     */
    public function validateImageFile(UploadedFile $file, int $maxSize = 2048): bool
    {
        // Check if it's an image
        if (!str_starts_with($file->getMimeType(), 'image/')) {
            return false;
        }

        // Check file size (in KB)
        if ($file->getSize() > $maxSize * 1024) {
            return false;
        }

        // Check allowed extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        return in_array($extension, $allowedExtensions);
    }

    /**
     * Get image dimensions
     */
    public function getImageDimensions(UploadedFile $file): array
    {
        $imageSize = getimagesize($file->getPathname());
        
        return [
            'width' => $imageSize[0] ?? 0,
            'height' => $imageSize[1] ?? 0,
        ];
    }

    /**
     * Generate responsive image variants
     */
    public function generateImageVariants(string $imagePath): array
    {
        $variants = [];
        $fullPath = storage_path('app/public/' . $imagePath);
        
        if (!file_exists($fullPath)) {
            return $variants;
        }

        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];

        // Define sizes
        $sizes = [
            'thumb' => ['width' => 300, 'height' => 200],
            'medium' => ['width' => 600, 'height' => 400],
            'large' => ['width' => 1200, 'height' => 800],
        ];

        foreach ($sizes as $sizeName => $dimensions) {
            $variantFilename = "{$filename}_{$sizeName}.{$extension}";
            $variantPath = "{$directory}/{$variantFilename}";
            $variantFullPath = storage_path('app/public/' . $variantPath);

            // Create resized image (this would require intervention/image package)
            // For now, we'll just reference the original
            $variants[$sizeName] = [
                'path' => $variantPath,
                'url' => asset('storage/' . $variantPath),
                'width' => $dimensions['width'],
                'height' => $dimensions['height'],
            ];
        }

        return $variants;
    }
}

