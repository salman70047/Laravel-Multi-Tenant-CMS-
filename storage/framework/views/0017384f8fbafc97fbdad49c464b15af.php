<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tenant Settings</h1>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please correct the following errors:</span>
            <ul class="mt-3 list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="<?php echo e(route('tenant.settings.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Tenant Name:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo e(old('name', $tenant->name)); ?>" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo e(old('description', $tenant->description)); ?></textarea>
            </div>

            <h2 class="text-xl font-semibold mt-6 mb-4">General Settings</h2>

            <div class="mb-4">
                <label for="theme" class="block text-gray-700 text-sm font-bold mb-2">Theme:</label>
                <select name="settings[theme]" id="theme" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="default" <?php echo e(old('settings.theme', $tenant->settings['theme'] ?? 'default') == 'default' ? 'selected' : ''); ?>>Default</option>
                    <option value="dark" <?php echo e(old('settings.theme', $tenant->settings['theme'] ?? '') == 'dark' ? 'selected' : ''); ?>>Dark</option>
                    <option value="light" <?php echo e(old('settings.theme', $tenant->settings['theme'] ?? '') == 'light' ? 'selected' : ''); ?>>Light</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="timezone" class="block text-gray-700 text-sm font-bold mb-2">Timezone:</label>
                <input type="text" name="settings[timezone]" id="timezone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo e(old('settings.timezone', $tenant->settings['timezone'] ?? '')); ?>">
            </div>

            <div class="mb-4">
                <label for="language" class="block text-gray-700 text-sm font-bold mb-2">Language:</label>
                <select name="settings[language]" id="language" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="en" <?php echo e(old('settings.language', $tenant->settings['language'] ?? 'en') == 'en' ? 'selected' : ''); ?>>English</option>
                    <option value="es" <?php echo e(old('settings.language', $tenant->settings['language'] ?? '') == 'es' ? 'selected' : ''); ?>>Spanish</option>
                    <option value="fr" <?php echo e(old('settings.language', $tenant->settings['language'] ?? '') == 'fr' ? 'selected' : ''); ?>>French</option>
                    <option value="de" <?php echo e(old('settings.language', $tenant->settings['language'] ?? '') == 'de' ? 'selected' : ''); ?>>German</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Settings
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/afifacheema/Downloads/multi-tenant-cms/resources/views/tenant/settings.blade.php ENDPATH**/ ?>