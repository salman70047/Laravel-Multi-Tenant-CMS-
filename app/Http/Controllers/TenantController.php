<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function dashboard()
    {
        $tenant = app('current_tenant');
        $stats = [
            'posts_count' => $tenant->posts()->count(),
            'categories_count' => $tenant->categories()->count(),
            'users_count' => $tenant->users()->count(),
            'published_posts' => $tenant->posts()->published()->count(),
            'draft_posts' => $tenant->posts()->draft()->count(),
        ];

        return view('dashboard', compact('tenant', 'stats'));
    }

    public function settings()
    {
        $tenant = app('current_tenant');
        return view('tenant.settings', compact('tenant'));
    }

    public function updateSettings(Request $request)
    {
        $tenant = app('current_tenant');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'settings.theme' => 'nullable|string|in:default,dark,light',
            'settings.timezone' => 'nullable|string',
            'settings.language' => 'nullable|string|in:en,es,fr,de',
        ]);

        $tenant->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'settings' => $validatedData['settings'] ?? [],
        ]);

        return redirect()->route('tenant.settings')->with('success', 'Tenant settings updated successfully!');
    }
}
