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

        factory(Product::class)->create()->toArray();
        $wishlist = factory(Wishlist::class)->create()->toArray();


        $response = $this->actingAs($user)->json('POST', 'api/wishlists', $wishlist);

        $response->assertStatus(201);

        dd($response->getContent());
    }
}
