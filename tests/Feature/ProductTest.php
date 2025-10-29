<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_create_a_product()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Test Product',
            'description' => 'Sample product description',
            'price' => 10000,
            'category' => 'Electronics',
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function user_can_read_products()
    {
        $this->actingAs($this->user);
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }


    /** @test */
    public function user_can_update_a_product()
    {
        $this->actingAs($this->user);
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 10000,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    /** @test */
    public function user_can_delete_a_product()
    {
        $this->actingAs($this->user);
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
