<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 500),
            'stock_quantity' => fake()->numberBetween(0, 100),
            'low_stock_threshold' => 5,
            'image' => null,
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock_quantity' => fake()->numberBetween(1, 4),
        ]);
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }
}
