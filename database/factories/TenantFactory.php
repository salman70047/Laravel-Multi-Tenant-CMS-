<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        $subdomain = Str::slug(fake()->domainWord());
        
        return [
            'name' => $name,
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.test',
            'is_active' => fake()->boolean(90), // 90% chance of being active
            'settings' => [
                'theme' => fake()->randomElement(['default', 'dark', 'light']),
                'timezone' => fake()->timezone(),
                'language' => fake()->randomElement(['en', 'es', 'fr', 'de']),
            ],
        ];
    }

    /**
     * Indicate that the tenant is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the tenant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
