<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Automatically set tenant_id when creating models
        $this->bootTenantScoping();
    }

    /**
     * Boot tenant scoping for models
     */
    private function bootTenantScoping(): void
    {
        // Auto-set tenant_id when creating new models
        foreach ([User::class, Category::class, Post::class] as $model) {
            $model::creating(function ($instance) {
                if (app()->bound('current_tenant') && app('current_tenant')) {
                    $instance->tenant_id = app('current_tenant')->id;
                }
            });
        }

        // Auto-set created_by and updated_by for posts
        Post::creating(function ($post) {
            if (auth()->check()) {
                $post->created_by = auth()->id();
                $post->updated_by = auth()->id();
            }
        });

        Post::updating(function ($post) {
            if (auth()->check()) {
                $post->updated_by = auth()->id();
            }
        });
    }
}
