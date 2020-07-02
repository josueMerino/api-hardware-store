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

        $product = factory(Product::class)->create();


        //dd($category->category);

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
