<?php

namespace Tests\Feature;

use App\Product;
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

        (ProductControllerTest::testCreatingProducts());
        $wishlist = factory(Wishlist::class)->create()->toArray();

        $response = $this->actingAs($user)->json('POST', 'api/wishlists', $wishlist);

        $response->assertStatus(201);

        dd($response->getContent());
    }
}
