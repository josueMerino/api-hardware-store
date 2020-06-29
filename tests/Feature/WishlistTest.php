<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use App\StockProduct;
use App\User;
use App\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Feature\ProductControllerTest;

class WishlistTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testCreateWishlist()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
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
        $wishlist = [
            'name' => 'Bruh',
            'product_id' => $product->id,
        ];

        Sanctum::actingAs($user, ['*']);

        $response = $this->json('POST', 'api/wishlists', $wishlist);

        dd($response->getContent());
        $response->assertStatus(201)
        ->dump();

        //
    }

    public function testUA()
    {

        $response = $this->json('GET', 'api/wishlists');

        $response->assertStatus(401);

        dd($response->getContent());
    }
}
