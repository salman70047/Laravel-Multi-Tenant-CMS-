<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Category: <?php echo e($category->name); ?></h1>
        <div class="flex space-x-4">
            <a href="<?php echo e(route('categories.edit', $category)); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit Category
            </a>
            <form action="<?php echo e(route('categories.destroy', $category)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this category and its associated posts?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Delete Category
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="mb-4">
            <p class="text-gray-700"><strong class="font-semibold">Description:</strong> <?php echo e($category->description ?? 'N/A'); ?></p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700"><strong class="font-semibold">Status:</strong> <?php echo e($category->is_active ? 'Active' : 'Inactive'); ?></p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700"><strong class="font-semibold">Created At:</strong> <?php echo e($category->created_at->format('M d, Y H:i A')); ?></p>
        </div>
        <div class="mb-4">
            <p class="text-gray-700"><strong class="font-semibold">Last Updated:</strong> <?php echo e($category->updated_at->format('M d, Y H:i A')); ?></p>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4">Posts in this Category</h2>

    <?php if($category->posts->count() > 0): ?>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Title
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Created By
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Created At
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $category->posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            <?php echo e($post->title); ?>

                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight <?php echo e($post->status === 'published' ? 'text-green-900' : ($post->status === 'draft' ? 'text-yellow-900' : 'text-gray-900')); ?>">
                                    <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full <?php echo e($post->status === 'published' ? 'bg-green-200' : ($post->status === 'draft' ? 'bg-yellow-200' : 'bg-gray-200')); ?>"></span>
                                    <span class="relative"><?php echo e(ucfirst($post->status)); ?></span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo e($post->createdBy->name ?? 'N/A'); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php echo e($post->created_at->format('M d, Y')); ?>

                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="<?php echo e(route('posts.show', $post)); ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-600">No posts found in this category.</p>
    <?php endif; ?>

    <div class="mt-8">
        <a href="<?php echo e(route('categories.index')); ?>" class="text-blue-500 hover:underline">&larr; Back to Categories</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/afifacheema/Downloads/multi-tenant-cms/resources/views/categories/show.blade.php ENDPATH**/ ?>