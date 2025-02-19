<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_fetch_all_products()
    {
        Product::factory(3)->create();
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    
        $jsonResponse = $response->json();
    
        $this->assertCount(4, $jsonResponse);
    }

    public function test_it_can_create_product()
    {
        $data = [
            'name' => 'Test Product',
            'price' => 100.50,
            'photo' => UploadedFile::fake()->create('product.jpg', 500, 'image/jpeg'),
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Test Product']);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_it_can_show_product_by_id()
    {
        $product = Product::factory()->create();
        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $product->name]);
    }

    public function test_it_can_update_product()
    {
        $product = Product::factory()->create();
        $updatedData = [
            'name' => 'Updated Product',
            'price' => 150.75,
            'photo' => UploadedFile::fake()->create('product.jpg', 500, 'image/jpeg'),
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updatedData);
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Updated Product']);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_it_can_delete_product()
    {
        $product = Product::factory()->create();
        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Product deleted']);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
