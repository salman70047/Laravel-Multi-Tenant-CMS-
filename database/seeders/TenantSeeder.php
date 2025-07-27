<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Tenant 1: TechCorp
        $tenant1 = Tenant::create([
            'name' => 'TechCorp Blog',
            'subdomain' => 'techcorp',
            'domain' => 'techcorp.test',
            'is_active' => true,
            'settings' => [
                'theme' => 'default',
                'timezone' => 'UTC',
                'language' => 'en',
                'description' => 'A technology company blog covering latest trends in software development.',
            ],
        ]);

        // Set current tenant for scoped model creation
        app()->instance('current_tenant', $tenant1);

        // Create user for tenant1
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@techcorp.test',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant1->id,
        ]);

        // Create categories for tenant1
        $techCategory = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'description' => 'Latest technology trends and news',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        $devCategory = Category::create([
            'name' => 'Development',
            'slug' => 'development',
            'description' => 'Software development tutorials and tips',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        $aiCategory = Category::create([
            'name' => 'Artificial Intelligence',
            'slug' => 'artificial-intelligence',
            'description' => 'AI and machine learning insights',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        // Create posts for tenant1
        Post::create([
            'title' => 'The Future of Web Development',
            'slug' => 'the-future-of-web-development',
            'content' => '<h2>Introduction</h2><p>Web development is constantly evolving, and staying ahead of the curve is crucial for developers and businesses alike. In this comprehensive guide, we\'ll explore the emerging trends and technologies that are shaping the future of web development.</p><h2>Key Trends</h2><p><strong>1. Progressive Web Apps (PWAs)</strong></p><p>Progressive Web Apps continue to gain traction as they offer native app-like experiences through web browsers. They provide offline functionality, push notifications, and improved performance.</p><p><strong>2. Serverless Architecture</strong></p><p>Serverless computing is revolutionizing how we build and deploy applications. It offers better scalability, reduced costs, and simplified infrastructure management.</p><p><strong>3. AI Integration</strong></p><p>Artificial Intelligence is becoming increasingly integrated into web applications, from chatbots to personalized user experiences.</p><h2>Conclusion</h2><p>The future of web development is exciting and full of opportunities. By staying informed about these trends and continuously learning new technologies, developers can build better, more efficient applications.</p>',
            'excerpt' => 'Explore the emerging trends and technologies that are shaping the future of web development, from PWAs to AI integration.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'category_id' => $devCategory->id,
            'tenant_id' => $tenant1->id,
            'created_by' => $user1->id,
            'updated_by' => $user1->id,
        ]);

        Post::create([
            'title' => 'Understanding Machine Learning Basics',
            'slug' => 'understanding-machine-learning-basics',
            'content' => '<h2>What is Machine Learning?</h2><p>Machine Learning is a subset of artificial intelligence that enables computers to learn and make decisions from data without being explicitly programmed for every task.</p><h2>Types of Machine Learning</h2><p><strong>Supervised Learning:</strong> Learning with labeled data to make predictions.</p><p><strong>Unsupervised Learning:</strong> Finding patterns in data without labels.</p><p><strong>Reinforcement Learning:</strong> Learning through interaction and feedback.</p><h2>Getting Started</h2><p>To begin your machine learning journey, start with Python and libraries like scikit-learn, TensorFlow, or PyTorch. Practice with real datasets and gradually build your understanding of algorithms and their applications.</p>',
            'excerpt' => 'A beginner-friendly introduction to machine learning concepts, types, and how to get started in this exciting field.',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'category_id' => $aiCategory->id,
            'tenant_id' => $tenant1->id,
            'created_by' => $user1->id,
            'updated_by' => $user1->id,
        ]);

        Post::create([
            'title' => 'Building Scalable APIs with Laravel',
            'slug' => 'building-scalable-apis-with-laravel',
            'content' => '<h2>Why Laravel for APIs?</h2><p>Laravel provides excellent tools for building robust and scalable APIs. With features like Eloquent ORM, middleware, and built-in authentication, it\'s an ideal choice for API development.</p><h2>Best Practices</h2><ul><li>Use API Resources for consistent data formatting</li><li>Implement proper authentication and authorization</li><li>Add rate limiting to prevent abuse</li><li>Use caching for improved performance</li><li>Implement proper error handling</li></ul><h2>Code Example</h2><p>Here\'s a simple example of a Laravel API controller:</p><pre><code>class PostController extends Controller {
    public function index() {
        return PostResource::collection(Post::paginate());
    }
}</code></pre>',
            'excerpt' => 'Learn best practices for building scalable and maintainable APIs using Laravel framework.',
            'status' => 'draft',
            'category_id' => $devCategory->id,
            'tenant_id' => $tenant1->id,
            'created_by' => $user1->id,
            'updated_by' => $user1->id,
        ]);

        // Create Tenant 2: FoodieWorld
        $tenant2 = Tenant::create([
            'name' => 'FoodieWorld',
            'subdomain' => 'foodieworld',
            'domain' => 'foodieworld.test',
            'is_active' => true,
            'settings' => [
                'theme' => 'default',
                'timezone' => 'America/New_York',
                'language' => 'en',
                'description' => 'A culinary blog featuring recipes, restaurant reviews, and food culture.',
            ],
        ]);

        // Set current tenant for scoped model creation
        app()->instance('current_tenant', $tenant2);

        // Create user for tenant2
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@foodieworld.test',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant2->id,
        ]);

        // Create categories for tenant2
        $recipesCategory = Category::create([
            'name' => 'Recipes',
            'slug' => 'recipes',
            'description' => 'Delicious recipes from around the world',
            'is_active' => true,
            'tenant_id' => $tenant2->id,
        ]);

        $reviewsCategory = Category::create([
            'name' => 'Restaurant Reviews',
            'slug' => 'restaurant-reviews',
            'description' => 'Honest reviews of restaurants and eateries',
            'is_active' => true,
            'tenant_id' => $tenant2->id,
        ]);

        $tipsCategory = Category::create([
            'name' => 'Cooking Tips',
            'slug' => 'cooking-tips',
            'description' => 'Professional cooking tips and techniques',
            'is_active' => true,
            'tenant_id' => $tenant2->id,
        ]);

        // Create posts for tenant2
        Post::create([
            'title' => 'Perfect Homemade Pizza Recipe',
            'slug' => 'perfect-homemade-pizza-recipe',
            'content' => '<h2>Ingredients</h2><p><strong>For the dough:</strong></p><ul><li>3 cups all-purpose flour</li><li>1 packet active dry yeast</li><li>1 cup warm water</li><li>2 tablespoons olive oil</li><li>1 teaspoon salt</li><li>1 teaspoon sugar</li></ul><p><strong>For the toppings:</strong></p><ul><li>1 cup pizza sauce</li><li>2 cups mozzarella cheese</li><li>Your favorite toppings</li></ul><h2>Instructions</h2><p>1. Dissolve yeast and sugar in warm water. Let sit for 5 minutes until foamy.</p><p>2. Mix flour and salt in a large bowl. Add yeast mixture and olive oil.</p><p>3. Knead the dough for 8-10 minutes until smooth and elastic.</p><p>4. Let rise in an oiled bowl for 1 hour.</p><p>5. Roll out dough, add sauce and toppings, then bake at 475°F for 12-15 minutes.</p>',
            'excerpt' => 'Learn how to make authentic homemade pizza with this step-by-step recipe that rivals your favorite pizzeria.',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'category_id' => $recipesCategory->id,
            'tenant_id' => $tenant2->id,
            'created_by' => $user2->id,
            'updated_by' => $user2->id,
        ]);

        Post::create([
            'title' => 'Review: Mario\'s Italian Bistro',
            'slug' => 'review-marios-italian-bistro',
            'content' => '<h2>Overall Experience</h2><p>Mario\'s Italian Bistro offers an authentic Italian dining experience in the heart of downtown. From the moment you walk in, you\'re transported to a cozy trattoria in Rome.</p><h2>Food Quality</h2><p>The pasta is clearly made fresh daily, and the sauces are rich and flavorful. I particularly enjoyed the Osso Buco, which was tender and perfectly seasoned. The wine selection is impressive, featuring both Italian and local options.</p><h2>Service</h2><p>The staff is knowledgeable and attentive without being intrusive. Our server provided excellent recommendations and was happy to accommodate dietary restrictions.</p><h2>Atmosphere</h2><p>The dim lighting and Italian music create a romantic atmosphere perfect for date nights or special occasions.</p><h2>Rating: 4.5/5</h2><p>Mario\'s Italian Bistro is definitely worth a visit. While it\'s on the pricier side, the quality of food and service justifies the cost.</p>',
            'excerpt' => 'An in-depth review of Mario\'s Italian Bistro, covering food quality, service, atmosphere, and overall value.',
            'status' => 'published',
            'published_at' => now()->subDays(2),
            'category_id' => $reviewsCategory->id,
            'tenant_id' => $tenant2->id,
            'created_by' => $user2->id,
            'updated_by' => $user2->id,
        ]);

        Post::create([
            'title' => '5 Essential Knife Skills Every Cook Should Master',
            'slug' => '5-essential-knife-skills-every-cook-should-master',
            'content' => '<h2>Introduction</h2><p>Proper knife skills are the foundation of good cooking. Mastering these techniques will not only make you more efficient in the kitchen but also safer and more confident.</p><h2>1. The Rock Chop</h2><p>Keep the tip of the knife on the cutting board and rock the blade down through the food. This technique is perfect for chopping herbs and vegetables.</p><h2>2. The Slice</h2><p>Draw the knife through the food in one smooth motion. Great for slicing meats and delicate vegetables.</p><h2>3. Julienne</p><p>Cut food into thin, matchstick-like strips. Perfect for stir-fries and garnishes.</p><h2>4. Brunoise</h2><p>A fine dice that creates tiny, uniform cubes. Essential for professional-looking dishes.</p><h2>5. Chiffonade</h2><p>Roll leafy greens or herbs and slice them into thin ribbons. Perfect for garnishing soups and salads.</p><h2>Safety Tips</h2><ul><li>Always keep your knives sharp</li><li>Use a proper cutting board</li><li>Keep your fingers curled and knuckles forward</li><li>Take your time and focus</li></ul>',
            'excerpt' => 'Master these five essential knife techniques to improve your cooking efficiency, safety, and presentation.',
            'status' => 'published',
            'published_at' => now()->subDays(1),
            'category_id' => $tipsCategory->id,
            'tenant_id' => $tenant2->id,
            'created_by' => $user2->id,
            'updated_by' => $user2->id,
        ]);

        $this->command->info('Created 2 tenants with demo data:');
        $this->command->info('- TechCorp Blog (techcorp.test) - Technology focused blog');
        $this->command->info('- FoodieWorld (foodieworld.test) - Food and cooking blog');
        $this->command->info('Both tenants have users, categories, and sample posts.');
    }
}
