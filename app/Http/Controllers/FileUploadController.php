<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    use HandlesFileUploads;

    /**
     * Upload image for rich text editor
     */
    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048', // 2MB max
        ]);

        try {
            $file = $request->file('image');
            
            if (!$this->validateImageFile($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image file.',
                ], 400);
            }

            $uploadResult = $this->handleFileUpload($file, 'editor-images');
            
            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully.',
                'data' => [
                    'url' => $uploadResult['url'],
                    'filename' => $uploadResult['original_name'],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload featured image
     */
    public function uploadFeaturedImage(Request $request)
    {
        $request->validate([
            'featured_image' => 'required|image|max:2048', // 2MB max
        ]);

        try {
            $file = $request->file('featured_image');
            
            if (!$this->validateImageFile($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image file.',
                ], 400);
            }

            $uploadResult = $this->handleFileUpload($file, 'featured-images');
            $dimensions = $this->getImageDimensions($file);
            
            return response()->json([
                'success' => true,
                'message' => 'Featured image uploaded successfully.',
                'data' => [
                    'url' => $uploadResult['url'],
                    'path' => $uploadResult['path'],
                    'filename' => $uploadResult['original_name'],
                    'size' => $uploadResult['size'],
                    'dimensions' => $dimensions,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload featured image: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete uploaded file
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');
            
            // Security check - ensure path is within allowed directories
            $allowedDirectories = ['editor-images', 'featured-images'];
            $isAllowed = false;
            
            foreach ($allowedDirectories as $dir) {
                if (str_starts_with($path, $dir . '/')) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file path.',
                ], 400);
            }

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get file information
     */
    public function getFileInfo(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');
            
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found.',
                ], 404);
            }

            $fullPath = Storage::disk('public')->path($path);
            $size = Storage::disk('public')->size($path);
            $lastModified = Storage::disk('public')->lastModified($path);
            
            $info = [
                'path' => $path,
                'url' => asset('storage/' . $path),
                'size' => $size,
                'size_human' => $this->formatBytes($size),
                'last_modified' => date('Y-m-d H:i:s', $lastModified),
            ];

            // Add image dimensions if it's an image
            if (str_starts_with(Storage::disk('public')->mimeType($path), 'image/')) {
                $imageSize = getimagesize($fullPath);
                $info['dimensions'] = [
                    'width' => $imageSize[0] ?? 0,
                    'height' => $imageSize[1] ?? 0,
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $info,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get file info: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
