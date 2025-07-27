<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class ApiEndpointTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a tenant for testing
        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'domain' => 'test.example.com',
            'subdomain' => 'test',
            'is_active' => true,
        ]);

        // Set current tenant
        app()->instance('current_tenant', $this->tenant);
    }

    public function test_api_posts_index()
    {
        // Create test posts
        $posts = Post::factory()->count(3)->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->getJson('/api/posts', [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'status',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ])
                ->assertJsonCount(3, 'data');
    }

    public function test_api_posts_store()
    {
        $category = Category::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $postData = [
            'title' => 'Test Post',
            'content' => 'Test content',
            'excerpt' => 'Test excerpt',
            'status' => 'published',
            'category_id' => $category->id,
        ];

        $response = $this->postJson('/api/posts', $postData, [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'status',
                        'tenant_id',
                    ]
                ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_api_posts_show()
    {
        $post = Post::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->getJson("/api/posts/{$post->id}", [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'title',
                        'content',
                        'status',
                        'tenant_id',
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $post->id,
                        'title' => $post->title,
                    ]
                ]);
    }

    public function test_api_posts_update()
    {
        $post = Post::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'status' => 'draft',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updateData, [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data'
                ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_api_posts_delete()
    {
        $post = Post::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->deleteJson("/api/posts/{$post->id}", [], [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_api_categories_index()
    {
        $categories = Category::factory()->count(2)->create([
            'tenant_id' => $this->tenant->id,
        ]);

        $response = $this->getJson('/api/categories', [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'description',
                            'is_active',
                        ]
                    ]
                ])
                ->assertJsonCount(2, 'data');
    }

    public function test_api_categories_store()
    {
        $categoryData = [
            'name' => 'Test Category',
            'description' => 'Test description',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/categories', $categoryData, [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data'
                ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    public function test_api_tenant_scoping_prevents_cross_tenant_access()
    {
        // Create another tenant with data
        $otherTenant = Tenant::create([
            'name' => 'Other Tenant',
            'domain' => 'other.example.com',
            'subdomain' => 'other',
            'is_active' => true,
        ]);

        $otherPost = Post::factory()->create([
            'tenant_id' => $otherTenant->id,
        ]);

        // Try to access other tenant's post from current tenant
        $response = $this->getJson("/api/posts/{$otherPost->id}", [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(404);
    }

    public function test_api_validation_errors()
    {
        // Test post creation with missing required fields
        $response = $this->postJson('/api/posts', [], [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);

        // Test category creation with missing required fields
        $response = $this->postJson('/api/categories', [], [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    public function test_api_posts_filtering_and_sorting()
    {
        $category = Category::factory()->create([
            'tenant_id' => $this->tenant->id,
        ]);

        // Create posts with different statuses and categories
        Post::factory()->create([
            'title' => 'Published Post',
            'status' => 'published',
            'category_id' => $category->id,
            'tenant_id' => $this->tenant->id,
        ]);

        Post::factory()->create([
            'title' => 'Draft Post',
            'status' => 'draft',
            'tenant_id' => $this->tenant->id,
        ]);

        // Test filtering by status
        $response = $this->getJson('/api/posts?status=published', [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');

        // Test filtering by category
        $response = $this->getJson("/api/posts?category={$category->id}", [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonCount(1, 'data');

        // Test sorting
        $response = $this->getJson('/api/posts?sort=title&direction=asc', [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Draft Post', $data[0]['title']);
    }

    public function test_api_tenant_info_endpoint()
    {
        $response = $this->getJson('/api/tenant', [
            'HTTP_HOST' => 'test.example.com'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'name',
                        'domain',
                        'subdomain',
                    ]
                ])
                ->assertJson([
                    'data' => [
                        'id' => $this->tenant->id,
                        'name' => $this->tenant->name,
                    ]
                ]);
    }

    public function test_api_file_upload_endpoints()
    {
        // Test featured image upload endpoint exists
        $response = $this->postJson('/api/upload/featured-image', [], [
            'HTTP_HOST' => 'test.example.com'
        ]);

        // Should return validation error, not 404
        $response->assertStatus(422);

        // Test editor image upload endpoint exists
        $response = $this->postJson('/api/upload/editor-image', [], [
            'HTTP_HOST' => 'test.example.com'
        ]);

        // Should return validation error, not 404
        $response->assertStatus(422);
    }
}
