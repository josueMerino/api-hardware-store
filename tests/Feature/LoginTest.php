<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;
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

        dd($response->getContent());

        $response->assertOk()
        ->assertJsonStructure(['access_token'])
        ->dump();


    }

    /**
     * Test Fail Login 401
     * When you send email and pass that are incorrect,
     * when that happens http status is 401
     */
    public function testFailLogin401()
    {

        $response = $this->json('POST', '/api/login', [
            'email' => 'test@tester.com',
            'password' => '12',
        ]);

        $response->assertStatus(401) //Unauthorized
        ->assertJsonStructure(['message']);
    }

    /**
     * When you send a completely invalid email and password
     * or simply you send nothing, the response is an http status 400
     */
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

        $token = Str::random(10);

        $auth = factory(User::class)->create();

        Sanctum::actingAs($auth, ['*']);

        $response = $this->json('GET', 'api/logout');

        //dd($response->getContent());
        $response->assertStatus(200);
    }

}
