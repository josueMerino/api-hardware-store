<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreatingUsers()
    {
        $this->withoutExceptionHandling();
        $user = [
            'name'=>'Josué Daniel',
            'last_name'=>'Merino Pineda',
            'email'=>'tester@test.com',
            'birth_date'=>$this->faker->date(),
            'password'=>password_hash('1234', PASSWORD_BCRYPT),
            'remember_token' => Str::random(10),

        ];

        $response = $this->json('POST','/api/user', $user);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'name',
            'last_name',
            'email',
            'birth_date',
            'image',
            'is_admin',
            'created_at',
        ]);
        dump($response);
    }
}
