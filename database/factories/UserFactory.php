<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $file = UploadedFile::fake()->image('profile.jpg');

    return [
        'name' => $faker->name,
        'last_name' =>$faker->lastName,
        'email' => 'test@tester.com',
        'email_verified_at' => now(),
        'password' => bcrypt('1234'), // password
        'remember_token' => Str::random(10),
        'birth_date' =>$faker->date(),
        'image'=>$file,


    ];
});
