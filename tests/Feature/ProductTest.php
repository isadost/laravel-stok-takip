<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function validData(Category $category, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $category->id,
            'name' => 'Test Ürün',
            'sku' => 'TEST-0001',
            'quantity' => 10,
            'price' => 99.90,
            'min_stock' => 5,
        ], $overrides);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'name' => 'Görünen Ürün']);

        $this->actingAs(User::factory()->create())
            ->get('/products')
            ->assertOk()
            ->assertSee('Görünen Ürün');
    }

    public function test_user_can_create_a_product(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post('/products', $this->validData($category))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['sku' => 'TEST-0001']);
    }

    public function test_duplicate_sku_is_rejected(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'sku' => 'TEST-0001']);

        $this->actingAs(User::factory()->create())
            ->post('/products', $this->validData($category))
            ->assertSessionHasErrors('sku');

        $this->assertDatabaseCount('products', 1);
    }

    public function test_negative_quantity_is_rejected(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post('/products', $this->validData($category, ['quantity' => -5]))
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('products', 0);
    }

    public function test_user_can_update_a_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'sku' => 'OLD-0001']);

        $this->actingAs(User::factory()->create())
            ->put("/products/{$product->id}", $this->validData($category, ['name' => 'Yeni Ad', 'sku' => 'OLD-0001']))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Yeni Ad']);
    }

    public function test_product_can_keep_its_own_sku_when_updated(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'sku' => 'KEEP-0001']);

        $this->actingAs(User::factory()->create())
            ->put("/products/{$product->id}", $this->validData($category, ['sku' => 'KEEP-0001']))
            ->assertSessionHasNoErrors();
    }

    public function test_sku_of_another_product_is_rejected_on_update(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id, 'sku' => 'TAKEN-0001']);
        $product = Product::factory()->create(['category_id' => $category->id, 'sku' => 'MINE-0001']);

        $this->actingAs(User::factory()->create())
            ->put("/products/{$product->id}", $this->validData($category, ['sku' => 'TAKEN-0001']))
            ->assertSessionHasErrors('sku');
    }

    public function test_user_can_delete_a_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs(User::factory()->create())
            ->delete("/products/{$product->id}")
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}