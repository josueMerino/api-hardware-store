<?php

namespace Tests\Feature;

use App\Category;
use App\Company;
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
    public function testCreateProduct()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');

        //We create the data required to product
        $category = factory(Category::class)->create();
        $company = factory(Company::class)->create();

        $product = [
            'id' => 1,
            'title' => $this->faker->title(),
            'price' => $this->faker->randomFloat(2),
            'information' => $this->faker->sentence(12),
            'image' => $image,
            //'number_of_items' => 12,
            'category_id' => $category->id,
            'companies' => json_encode([
                ['company_id' => $company->id,
                'number_of_items' => random_int(2,90)
                ],
            ]),
            //'company_id' => $company->companies,
        ];

        $response = $this->json('POST', 'api/products', $product);

        $response->assertStatus(201)
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
            'items',
            'category',
        ]);

        dd($response->getContent());

    }

    public function testUpdatePS()
    {
        $this->withoutExceptionHandling();
        $image = UploadedFile::fake()->image('product.jpg');
        $category = factory(Category::class)->create();
        $product = factory(Product::class)->create();
        $stockProduct = factory(StockProduct::class)->create();


        //dd($category->category);
        $response = $this->json('POST', 'api/products', [
            'title' => $product->title,
            'price' => $product->price,
            'information' => $product->information,
            'image' => $product->image,
            'number_of_items' => $stockProduct->number_of_items,
            'category' => $category->category,
        ]);

        $productUpdate = [
            'title' => 'Pepito',
            'number_of_items' => 16,
            //'category' => 'mouses',
        ];


        $response = $this->json('PUT', "/api/products/$product->id", $productUpdate);

        $response->assertOk()
        ->dump();

        dd($response->getContent());


    }

    public function testIndex()
    {

    }
}
