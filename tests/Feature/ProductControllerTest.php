<?php

namespace Tests\Feature;

use App\Product;
use App\StockProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateProductStock()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $product = [
            'title' =>$this->faker->name,
            'price' =>126.20,
            'information' => $this->faker->paragraph(1),
            'image' =>$image,
            'number_of_items' => 12,
        ];

        $response = $this->json('POST', '/api/products', $product);


        $response->dump()
        ->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'title',
            'price',
            'information',
            'image',
            'items'
        ]);

        dd($response->getContent());

    }

    public function testErrorCreateProduct()
    {
        //$this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $producto = [
            'title' => '',
            'price' =>'',
            'information' => '',
            'image' =>$image,
        ];

        $response = $this->json('POST', 'api/products', $producto);

        $response->assertStatus(400)
        ->dump();

    }

    public function testUpdateProductStock()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $product = factory(Product::class)->create();
        $stockProduct = factory(StockProduct::class)->create();

        $response = $this->json('POST', 'api/products', [
            'title' => $product->title,
            'price' => $product->price,
            'information' => $product->information,
            'image' => $product->image,
            'number_of_items' => $stockProduct->number_of_items
        ]);

        $productUpdate = [
            'title' => 'Pepito',
            'number_of_items' => 16,
        ];


        $response = $this->json('PUT', "/api/products/$product->id", $productUpdate);

        $response->assertOk()
        ->dump();

        dd($response->getContent());


    }

    public function testCreatePSC()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $product = [
            'title' =>$this->faker->name,
            'price' =>126.20,
            'information' => $this->faker->paragraph(1),
            'image' =>$image,
            'number_of_items' => 12,
            'category' => 'laptop',
        ];

        $response = $this->json('POST', '/api/products', $product);


        $response->dump()
        ->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'title',
            'price',
            'information',
            'image',
            'items'
        ]);

        dd($response->getContent());

    }
}
