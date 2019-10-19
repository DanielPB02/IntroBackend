<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{

    use RefreshDatabase;

    /**
     * CREATE-1
     */
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

    /**
     * CREATE-2
     */
    public function test_client_can_not_create_a_product_without_name()
    {
        $product =[
            'price' => '20'
        ];

        $response = $this->json('POST', '/api/products', $product);

        $response->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                "code" => "ERROR-1",
                "title" => "Unprocessable Entity"
            ]]
            );
    }

    /**
     * CREATE-3
     */
    public function test_client_can_not_create_a_product_without_price()
    {
        $product =[
            'name' => 'producto'
        ];

        $response = $this->json('POST', '/api/products', $product);

        $response->assertStatus(422)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-1",
                        "title" => "Unprocessable Entity"
                    ]]
            );
    }

    /**
     * CREATE-4
     */
    public function test_client_can_not_create_a_product_with_price_not_numeric()
    {
        $product = [
            'name' => 'producto',
            'price' => 'precio'
        ];

        $response = $this->json('POST', '/api/products', $product);

        $response->assertStatus(422)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-1",
                        "title" => "Unprocessable Entity"
                    ]]
            );
    }

    /**
     * CREATE-5
     */
    public function test_client_can_not_create_a_product_with_price_less_than_zero()
    {
        $product = [
            'name' => 'producto',
            'price' => '-20'
        ];

        $response = $this->json('POST', '/api/products', $product);

        $response->assertStatus(422)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-1",
                        "title" => "Unprocessable Entity"
                    ]]
            );
    }

    /**
     * UPDATE-1
     */
    public function test_client_can_update_a_product()
    {
        $product = factory(Product::class)->create();
        $id = $product->id;

        $productUpdated = [
            'name' => 'enchiladas yumi',
            'price' => '10.10'
        ];

        $response = $this->json('PUT', '/api/products/'.$id, $productUpdated);

        $response->assertStatus(200)
            ->assertJson([
                "name" => "enchiladas yumi",
                'price' => '10.10'
            ]);
    }

    /**
     * UPDATE-2
     */
    public function test_client_can_not_update_a_product_with_price_not_numeric()
    {
        $product = factory(Product::class)->create();
        $id = $product->id;

        $productUpdated = [
            'price' => 'el precio de una enchilada'
        ];

        $response = $this->json('PUT', '/api/products/'.$id, $productUpdated);

        $response->assertStatus(422)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-1",
                        "title" => "Unprocessable Entity"
                    ]]
            );
    }

    /**
     * UPDATE-3
     */
    public function test_client_can_not_update_a_product_with_price_less_than_zero()
    {
        $product = factory(Product::class)->create();
        $id = $product->id;

        $productUpdated = [
            'price' => '-20'
        ];

        $response = $this->json('PUT', '/api/products/'.$id, $productUpdated);

        $response->assertStatus(422)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-1",
                        "title" => "Unprocessable Entity"
                    ]]
            );
    }

    /**
     * UPDATE-4
     */
    public function test_client_can_not_update_a_product_that_does_not_exist()
    {
        $productUpdated = [
            'name' => 'enchiladas muy ricas',
            'price' => '11.11'
        ];

        $response = $this->json('PUT', '/api/products/-1', $productUpdated);

        $response->assertStatus(404)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-2",
                        "title" => "Not Found"
                    ]]
            );
    }

    /**
     * SHOW-1
     */
    public function test_show_a_product()
    {
        $product = factory(Product::class)->create();
        $id = $product->id;

        $response = $this->json('GET', '/api/products/'. $id );

        $response->assertStatus(200);
        $decodedResponse = $response->decodeResponseJson();
        $this->assertJsonStringEqualsJsonString(
            json_encode($product),
            json_encode($decodedResponse)
        );
    }

    /**
     * SHOW-2
     */
    public function test_can_not_show_a_product()
    {
        $response = $this->json('GET', '/api/products/-1');

        $response->assertStatus(404)
        ->assertExactJson([
            "errors" => [
                "code" => "ERROR-2",
                "title" => "Not Found"
            ]]
        );
    }

    /**
     * LIST-1
     */
    public function test_show_all_products()
    {
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $response = $this->json('GET', 'api/products');

        $response->assertStatus(200)
            ->assertJson([
                [
                    "id" => $product1->id,
                    "name" => $product1->name,
                    "price" => $product1->price
                ],
                [
                    "id" => $product2->id,
                    "name" => $product2->name,
                    "price" => $product2->price
                ]
            ]);
    }

    /**
     * LIST-2
     */
    public function test_show_no_products()
    {
        $response = $this->json('GET', 'api/products');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    /**
     * DELETE-1
     */
    public function test_client_can_delete_a_product()
    {
        $product = factory(Product::class)->create();
        $id = $product->id;

        $response = $this->json('DELETE','api/products/'.$id);
        $response->assertStatus(204);
    }

    /**
     * DELETE-2
     */
    public function test_client_can_not_delete_a_product()
    {
        $response = $this->json('DELETE','api/products/-1');
        $response->assertStatus(404)
            ->assertExactJson([
                    "errors" => [
                        "code" => "ERROR-2",
                        "title" => "Not Found"
                    ]]
            );
    }
}