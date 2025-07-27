<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class TenantScopingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $tenant1;
    protected $tenant2;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create two tenants for testing
        $this->tenant1 = Tenant::create([
            'name' => 'Tenant One',
            'domain' => 'tenant1.test',
            'subdomain' => 'tenant1',
            'is_active' => true,
        ]);

        $this->tenant2 = Tenant::create([
            'name' => 'Tenant Two', 
            'domain' => 'tenant2.test',
            'subdomain' => 'tenant2',
            'is_active' => true,
        ]);
    }

    public function test_tenant_middleware_resolves_correct_tenant()
    {
        // Test subdomain resolution
        $response = $this->get('/', ['HTTP_HOST' => 'tenant1.test']);
        $response->assertStatus(200);
        
        // The middleware should set the current tenant
        $this->assertEquals($this->tenant1->id, app('current_tenant')?->id);
    }

    public function test_posts_are_scoped_to_tenant()
    {
        // Create posts for each tenant
        $post1 = Post::create([
            'title' => 'Post for Tenant 1',
            'content' => 'Content for tenant 1',
            'status' => 'published',
            'tenant_id' => $this->tenant1->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $post2 = Post::create([
            'title' => 'Post for Tenant 2',
            'content' => 'Content for tenant 2', 
            'status' => 'published',
            'tenant_id' => $this->tenant2->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Set current tenant to tenant1
        app()->instance('current_tenant', $this->tenant1);

        // Should only see posts for tenant1
        $posts = Post::all();
        $this->assertCount(1, $posts);
        $this->assertEquals($post1->id, $posts->first()->id);
        $this->assertEquals($this->tenant1->id, $posts->first()->tenant_id);

        // Switch to tenant2
        app()->instance('current_tenant', $this->tenant2);

        // Should only see posts for tenant2
        $posts = Post::all();
        $this->assertCount(1, $posts);
        $this->assertEquals($post2->id, $posts->first()->id);
        $this->assertEquals($this->tenant2->id, $posts->first()->tenant_id);
    }

    public function test_categories_are_scoped_to_tenant()
    {
        // Create categories for each tenant
        $category1 = Category::create([
            'name' => 'Category for Tenant 1',
            'description' => 'Description for tenant 1',
            'is_active' => true,
            'tenant_id' => $this->tenant1->id,
        ]);

        $category2 = Category::create([
            'name' => 'Category for Tenant 2',
            'description' => 'Description for tenant 2',
            'is_active' => true,
            'tenant_id' => $this->tenant2->id,
        ]);

        // Set current tenant to tenant1
        app()->instance('current_tenant', $this->tenant1);

        // Should only see categories for tenant1
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $this->assertEquals($category1->id, $categories->first()->id);
        $this->assertEquals($this->tenant1->id, $categories->first()->tenant_id);

        // Switch to tenant2
        app()->instance('current_tenant', $this->tenant2);

        // Should only see categories for tenant2
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $this->assertEquals($category2->id, $categories->first()->id);
        $this->assertEquals($this->tenant2->id, $categories->first()->tenant_id);
    }

    public function test_users_are_scoped_to_tenant()
    {
        // Create users for each tenant
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@tenant1.test',
            'password' => bcrypt('password'),
            'tenant_id' => $this->tenant1->id,
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@tenant2.test',
            'password' => bcrypt('password'),
            'tenant_id' => $this->tenant2->id,
        ]);

        // Set current tenant to tenant1
        app()->instance('current_tenant', $this->tenant1);

        // Should only see users for tenant1
        $users = User::all();
        $this->assertCount(1, $users);
        $this->assertEquals($user1->id, $users->first()->id);
        $this->assertEquals($this->tenant1->id, $users->first()->tenant_id);

        // Switch to tenant2
        app()->instance('current_tenant', $this->tenant2);

        // Should only see users for tenant2
        $users = User::all();
        $this->assertCount(1, $users);
        $this->assertEquals($user2->id, $users->first()->id);
        $this->assertEquals($this->tenant2->id, $users->first()->tenant_id);
    }

    public function test_cross_tenant_data_isolation()
    {
        // Create data for tenant1
        $category1 = Category::create([
            'name' => 'Tech Category',
            'tenant_id' => $this->tenant1->id,
            'is_active' => true,
        ]);

        $post1 = Post::create([
            'title' => 'Tech Post',
            'content' => 'Tech content',
            'status' => 'published',
            'category_id' => $category1->id,
            'tenant_id' => $this->tenant1->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Set current tenant to tenant2
        app()->instance('current_tenant', $this->tenant2);

        // Tenant2 should not be able to access tenant1's data
        $this->assertCount(0, Post::all());
        $this->assertCount(0, Category::all());

        // Even direct queries should not return cross-tenant data
        $this->assertNull(Post::find($post1->id));
        $this->assertNull(Category::find($category1->id));
    }

    public function test_tenant_relationships_work_correctly()
    {
        // Set current tenant
        app()->instance('current_tenant', $this->tenant1);

        // Create category and post for tenant1
        $category = Category::create([
            'name' => 'News Category',
            'tenant_id' => $this->tenant1->id,
            'is_active' => true,
        ]);

        $post = Post::create([
            'title' => 'News Post',
            'content' => 'News content',
            'status' => 'published',
            'category_id' => $category->id,
            'tenant_id' => $this->tenant1->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Test relationships
        $this->assertEquals($category->id, $post->category->id);
        $this->assertEquals($post->id, $category->posts->first()->id);
        $this->assertEquals($this->tenant1->id, $post->tenant->id);
        $this->assertEquals($this->tenant1->id, $category->tenant->id);
    }

    public function test_creating_model_automatically_sets_tenant_id()
    {
        // Set current tenant
        app()->instance('current_tenant', $this->tenant1);

        // Create models without explicitly setting tenant_id
        $category = Category::create([
            'name' => 'Auto Tenant Category',
            'is_active' => true,
        ]);

        $post = Post::create([
            'title' => 'Auto Tenant Post',
            'content' => 'Auto tenant content',
            'status' => 'draft',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Should automatically have tenant_id set
        $this->assertEquals($this->tenant1->id, $category->tenant_id);
        $this->assertEquals($this->tenant1->id, $post->tenant_id);
    }

    public function test_updating_model_preserves_tenant_id()
    {
        // Set current tenant
        app()->instance('current_tenant', $this->tenant1);

        // Create a post
        $post = Post::create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'status' => 'draft',
            'tenant_id' => $this->tenant1->id,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Update the post
        $post->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);

        // Tenant ID should remain unchanged
        $this->assertEquals($this->tenant1->id, $post->fresh()->tenant_id);
    }

    public function test_inactive_tenant_cannot_be_accessed()
    {
        // Deactivate tenant
        $this->tenant1->update(['is_active' => false]);

        // Attempt to access with inactive tenant should fail
        $response = $this->get('/', ['HTTP_HOST' => 'tenant1.test']);
        $response->assertStatus(404); // Or whatever status your middleware returns
    }
}
