<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use App\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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

        $product = factory(Product::class)->create()->toArray();
        $wishlist = factory(Wishlist::class)->create()->toArray();

        Sanctum::actingAs($user, ['*']);
        $response = $this->json('POST', 'api/wishlists', $wishlist);

        $response->assertStatus(201);
    }
}
