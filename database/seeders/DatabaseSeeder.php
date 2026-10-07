<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach (['Elektronik', 'Kırtasiye', 'Gıda', 'Temizlik', 'Giyim'] as $name) {
            Category::create(['name' => $name]);
        }

        Product::factory()->count(50)->create();
    }
}