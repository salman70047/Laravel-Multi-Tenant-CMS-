<?php $__env->startSection('title', $post->title); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <?php echo e($post->title); ?>

            </h2>
            <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                <?php if($post->status === 'published'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Published
                    </span>
                <?php elseif($post->status === 'draft'): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        Draft
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Archived
                    </span>
                <?php endif; ?>
                
                <?php if($post->category): ?>
                    <span>•</span>
                    <span class="text-purple-600"><?php echo e($post->category->name); ?></span>
                <?php endif; ?>
                
                <span>•</span>
                <span>Created <?php echo e($post->created_at->diffForHumans()); ?></span>
                
                <?php if($post->creator): ?>
                    <span>by <?php echo e($post->creator->name); ?></span>
                <?php endif; ?>
                
                <?php if($post->updated_at != $post->created_at): ?>
                    <span>•</span>
                    <span>Updated <?php echo e($post->updated_at->diffForHumans()); ?></span>
                    <?php if($post->updater && $post->updater->id != $post->creator?->id): ?>
                        <span>by <?php echo e($post->updater->name); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-2">
            <a href="<?php echo e(route('posts.edit', $post)); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Post
            </a>
            <a href="<?php echo e(route('posts.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Posts
            </a>
        </div>
    </div>

    <!-- Post Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <?php if($post->getFirstMediaUrl('featured_image')): ?>
            <div class="aspect-w-16 aspect-h-9">
                <img src="<?php echo e($post->getFirstMediaUrl('featured_image', 'large')); ?>" alt="<?php echo e($post->title); ?>" class="w-full h-64 object-cover">
            </div>
        <?php endif; ?>
        
        <div class="px-4 py-5 sm:p-6">
            <?php if($post->excerpt): ?>
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Excerpt</h3>
                    <p class="text-gray-600 italic"><?php echo e($post->excerpt); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="prose prose-indigo max-w-none">
                <?php echo $post->content; ?>

            </div>
        </div>
    </div>

    <!-- Post Meta -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Post Information</h3>
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php if($post->status === 'published'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        <?php elseif($post->status === 'draft'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Archived
                            </span>
                        <?php endif; ?>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php if($post->category): ?>
                            <a href="<?php echo e(route('categories.show', $post->category)); ?>" class="text-purple-600 hover:text-purple-500">
                                <?php echo e($post->category->name); ?>

                            </a>
                        <?php else: ?>
                            <span class="text-gray-400">No category</span>
                        <?php endif; ?>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo e($post->created_at->format('F j, Y \a\t g:i A')); ?>

                        <?php if($post->creator): ?>
                            by <?php echo e($post->creator->name); ?>

                        <?php endif; ?>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <?php echo e($post->updated_at->format('F j, Y \a\t g:i A')); ?>

                        <?php if($post->updater): ?>
                            by <?php echo e($post->updater->name); ?>

                        <?php endif; ?>
                    </dd>
                </div>
                
                <?php if($post->published_at): ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Published</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <?php echo e($post->published_at->format('F j, Y \a\t g:i A')); ?>

                        </dd>
                    </div>
                <?php endif; ?>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-mono">
                        <?php echo e($post->slug); ?>

                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('posts.edit', $post)); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Post
                </a>
                
                <form method="POST" action="<?php echo e(route('posts.destroy', $post)); ?>" class="inline" onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Post
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .prose {
        max-width: none;
    }
    .prose h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .prose h2 {
        font-size: 1.875rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .prose h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }
    .prose p {
        margin-bottom: 1rem;
        line-height: 1.75;
    }
    .prose ul, .prose ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
    .prose strong {
        font-weight: 600;
    }
    .prose em {
        font-style: italic;
    }
</style>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/afifacheema/Downloads/multi-tenant-cms/resources/views/posts/show.blade.php ENDPATH**/ ?>