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
    public function testCreatingProducts()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product');
        $producto = [
            'name' =>$this->faker->name,
            'price' =>$this->faker->randomFloat(),
            'information' => $this->faker->paragraph(),
            'image' =>$image,
        ];

        $response = $this->json('POST', '/api/products', $producto);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'name',
            'price',
            'information',
            'image',
        ]);
    }

    public function testShowOneProduct()
    {

    }
}
