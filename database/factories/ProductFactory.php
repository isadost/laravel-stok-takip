<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'name' => fake()->words(3, true),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'quantity' => fake()->numberBetween(0, 200),
            'price' => fake()->randomFloat(2, 5, 2000),
            'min_stock' => fake()->numberBetween(5, 30),
        ];
    }
}