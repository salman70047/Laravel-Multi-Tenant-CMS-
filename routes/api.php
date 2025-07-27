<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\FileUploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['tenant'])->group(function () {
    // Categories API
    Route::apiResource('categories', CategoryController::class);
    
    // Posts API
    Route::apiResource('posts', PostController::class);
    
    // Featured image upload for posts
    Route::post('posts/{post}/featured-image', [PostController::class, 'uploadFeaturedImage'])
         ->name('posts.featured-image');
    
    // File uploads
    Route::post('/upload/editor-image', [FileUploadController::class, 'uploadEditorImage']);
    Route::post('/upload/featured-image', [FileUploadController::class, 'uploadFeaturedImage']);
    Route::delete('/upload/file', [FileUploadController::class, 'deleteFile']);
    Route::get('/upload/file-info', [FileUploadController::class, 'getFileInfo']);
    
    // Tenant info
    Route::get('/tenant', function () {
        $tenant = app('current_tenant');
        return response()->json([
            'success' => true,
            'data' => $tenant,
        ]);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
