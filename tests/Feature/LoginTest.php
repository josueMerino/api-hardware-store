<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    use WithFaker;
    public function testLogin()
    {
        $this->withoutExceptionHandling();
        //The user is logging
        factory(User::class)->create([
            'email' => 'test@tester.com',
            'password' => bcrypt('1234'),
        ]);

        $response = $this->json('POST', 'api/login', [
            'email' => 'test@tester.com',
            'password' => '1234',
        ]);


        $response->assertOk()
        ->assertJsonStructure(['access_token']);
    }

    public function testFailLogin401()
    {

        $response = $this->json('POST', '/api/login', [
            'email' => 'test@tester.com',
            'password' => '12',
        ]);

        $response->assertStatus(401) //Unauthorized
        ->assertJsonStructure(['message']);
    }

    public function testFailLogin400()
    {

        $response = $this->json('POST', '/api/login', [
            'email' => 'tes',
            'password' => '12',
        ]);

        $response->assertStatus(400) //Bad Request
        ->assertJsonStructure(['message', 'error']);
    }

    public function testLogout()
    {
        //We login the user
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create()->toArray();

        $auth = factory(User::class)->create();

        //Sanctum::actingAs($auth, ['*']);

        $response = $this->actingAs($auth)->json('POST', 'api/logout', $user);

        $response->assertStatus(200);
    }

}
