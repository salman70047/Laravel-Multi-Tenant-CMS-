<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FileUploadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['tenant'])->group(function () {
    // Dashboard
    Route::get('/', [TenantController::class, 'dashboard'])->name('dashboard');
    
    // Tenant settings
    Route::get('/settings', [TenantController::class, 'settings'])->name('tenant.settings');
    Route::put('/settings', [TenantController::class, 'updateSettings'])->name('tenant.settings.update');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Posts
    Route::resource('posts', PostController::class);
    
    // File uploads
    Route::post('/upload/editor-image', [FileUploadController::class, 'uploadEditorImage'])->name('upload.editor-image');
    Route::post('/upload/featured-image', [FileUploadController::class, 'uploadFeaturedImage'])->name('upload.featured-image');
    Route::delete('/upload/file', [FileUploadController::class, 'deleteFile'])->name('upload.delete');
    Route::get('/upload/file-info', [FileUploadController::class, 'getFileInfo'])->name('upload.file-info');
});
