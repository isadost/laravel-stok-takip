<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/categories')->assertRedirect('/login');
    }

    public function test_user_can_create_a_category(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/categories', ['name' => 'Oyuncak'])
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'Oyuncak']);
    }

    public function test_duplicate_category_name_is_rejected(): void
    {
        Category::factory()->create(['name' => 'Oyuncak']);

        $this->actingAs(User::factory()->create())
            ->post('/categories', ['name' => 'Oyuncak'])
            ->assertSessionHasErrors('name');
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs(User::factory()->create())
            ->delete("/categories/{$category->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/categories/{$category->id}")
            ->assertRedirect(route('categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}