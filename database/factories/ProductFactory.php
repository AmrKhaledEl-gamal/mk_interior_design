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
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(4),
            'category_id' => \App\Models\Category::factory(),
            'original_price' => $this->faker->randomFloat(2, 10, 1000),
            'discount_type' => $this->faker->randomElement(['none', 'percentage', 'fixed']),
            'discount_amount' => $this->faker->randomFloat(2, 0, 100),
            'current_stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['active', 'inactive', 'out_of_stock']),
            'top_product' => $this->faker->boolean(20),
            'show_in_home_banner' => $this->faker->boolean(15)
        ];
    }
}
