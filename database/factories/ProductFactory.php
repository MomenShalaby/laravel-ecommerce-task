<?php

namespace Database\Factories;

use App\Models\Category;
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
        $categoryIds = Category::pluck('id')->toArray();
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->text(),
            'image' => 'https://www.path-tec.com/wp-content/uploads/2015/02/shop-placeholder.png',
            'price' => fake()->randomFloat(2, 0, 9999999.99),
            'category_id' => fake()->randomElement($categoryIds),
        ];
    }
}
