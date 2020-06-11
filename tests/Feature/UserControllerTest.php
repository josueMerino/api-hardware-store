<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    //Next TEST you must verify if the data is in db
    use RefreshDatabase;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return json
     */

    public function testShowOneUser()
    {
        //Only the admin can have access to this
        $user = factory(User::class)->create();

        $response = $this->json('GET', "/api/users/$user->id");

        $response->assertStatus(200)
        ->dump();

        dd($response);
    }

    public function testUpdateUser()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $file = UploadedFile::fake()->image('profile.jpg');

        $response = $this->json('PUT', "/api/users/$user->id", [
            'name'=>'Josué Daniel',
            'last_name'=>'Merino Pineda',
            'email'=>'tester@test.com',
            'birth_date'=>$this->faker->date(),
            'password'=>password_hash('1234', PASSWORD_BCRYPT),
            'country' =>$this->faker->country,
            'image'=>$file,

        ]);

        //dump($user, $response);

        $response
        ->assertStatus(200)
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

    }

    public function testDeleteOneUser()
    {
        $user = factory(User::class)->create();

        $response = $this->json('DELETE', "/api/users/$user->id");

        $response->assertStatus(204)
        ->assertSee(null);
    }

    public function testShowAllUsers()
    {
        //Only the admin can have access to this
        $user = factory(User::class, 10)->create();

        $response = $this ->json('GET',"/api/users");

        $response->assertStatus(200)
        ->assertJsonStructure([
            '*' =>[
                'id',
                'name',
                'last_name',
                'email',
                'birth_date',
                'image',
                'is_admin',
                'created_at',
            ]
        ]);
    }


}
