<?php

namespace Tests\Feature;

use App\Card;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase, WithFaker;
    public function testAddCard()
    {
        $user = factory(User::class)->create();

        $card = [
            'card_number' =>$this->faker->creditCardNumber,
        ];

        $response = $this->actingAs($user)->json('POST','api/cards', $card);

        $response->assertStatus(201)
        ->dump();


    }

    public function testErrorAddCard()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->json('POST', 'api/cards',[
            'card_number' => '',
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors('card_number');
    }

    public function testDeleteCard()
    {
        $user = factory(User::class)->create();

        $card = factory(Card::class)->create();

        $response = $this->actingAs($user)->json('DELETE', "api/cards/$card->id");

        $response->assertStatus(204)
        ->assertSee(null);
    }
}
