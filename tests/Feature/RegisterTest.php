<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    use WithFaker;

    public function testRegister()
    {
        $this->withoutExceptionHandling();

        // Call to the class Storage to take a picture

        $file = UploadedFile::fake()->image('profile.jpg');
        $user = [
            'name'=>'Josué Daniel',
            'last_name'=>'Merino Pineda',
            'email'=>'tester@test.com',
            'birth_date'=>$this->faker->date('d-m-Y'),
            'country' => $this->faker->country,
            'password'=>password_hash('1234', PASSWORD_BCRYPT),
            'image'=>$file,

        ];

        $response = $this->json('POST','api/register', $user);

        $response->assertStatus(201)
        ->assertJsonStructure([
            'id',
            'name',
            'last_name',
            'email',
            'birth_date',
            'country',
            'image',
            'is_admin',
            'created_at',
        ]);

        dd($response->getContent());

    }

    public function testFailRegister()
    {
        $this->withoutExceptionHandling();
        $file = UploadedFile::fake()->image('profile.jpg');
        $user = [
            'name'=>'a',
            'last_name'=>'a',
            'email'=>'testertest.com',
            'birth_date'=>'r',
            'password'=>'3560',
            'image'=>$file,

        ];

        $response = $this->json('POST','/api/register', $user);

        $response->assertStatus(422)
        ->assertJsonValidationErrors('email');

    }

}
