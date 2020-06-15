<?php

namespace Tests\Feature;

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
    public function testCreateProduct()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $producto = [
            'title' =>$this->faker->name,
            'price' =>$this->faker->randomFloat(2,4),
            'information' => $this->faker->paragraph(),
            'image' =>$image,
        ];

        $response = $this->json('POST', '/api/products', $producto);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'title',
            'price',
            'information',
            'image',
        ])
        ->dump();

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
            //'number_of_items' => []
        ]);

        dd($response->getContent());

    }
}
