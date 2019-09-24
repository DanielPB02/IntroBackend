<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_client_can_create_a_product()
    {
        // Given
        $productData = [
            'name' => 'Super Product',
            'price' => '23.30'
        ];

        // When
        $response = $this->json('POST', '/api/products', $productData); 

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
        
        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'id',
            'name',
            'price'
        ]);

        // Assert the product was created
        // with the correct data
        $response->assertJsonFragment([
            'name' => 'Super Product',
            'price' => '23.30'
        ]);
        
        $body = $response->decodeResponseJson();

        // Assert product is on the database
        $this->assertDatabaseHas(
            'products',
            [
                'id' => $body['id'],
                'name' => 'Super Product',
                'price' => '23.30'
            ]
        );
    }

    public function test_update_a_product()
    {
        //Given
        $product = factory(Product::class)->create([
            'id' => 1,
            'name' => 'producto1',
            'price' => '32.2',
        ]);
        $productUpdated = [
            'name'=>'producto2',
            'price'=>'44.3'
        ];
        //When
        $response = $this->json('PUT', '/api/products/1', $productUpdated);

        //Then
        //Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the product was updated
        // with the correct data
        $response->assertJson([
            'name' => 'producto2',
            'price' => '44.3'
        ]);
    }
    
    public function test_show_a_product()
    {

        $product = factory(Product::class)->create([
            'id' => 1,
            'name' => 'producto1',
            'price' => '32.2',
        ]);

        $response = $this->json('GET','api/products/1');
        $response->assertStatus(200)
        ->assertJson([
            'name' => 'producto1',
            'price' => '32.2'
        ]);
    }

    public function test_show_all_products()
    {
        $product = factory(Product::class)->create([
            'id'=>1,
            'name'=>'producto1',
            'price'=>'33.3'
        ]);

        $product2 = factory(Product::class)->create([
            'id'=>2,
            'name'=>'producto2',
            'price'=>'44.4'
        ]);

        $response = $this->json('GET', 'api/products');
        $response->assertStatus(200)
        ->assertJson([
            [
            
                'name'=>'producto1',
                'price'=>'33.3'
            ],
            [
                'name'=>'producto2',
                'price'=>'44.4'
            
            ]
        ]);
    }

    public function test_delete_a_product(){
        $product = factory(Product::class)->create([
            'id'=> 1,
            'name'=>'producto1',
            'price'=>'33.3'
        ]);

        $response = $this->json('DELETE','api/products/1');
        $response->assertStatus(200);
    }
}