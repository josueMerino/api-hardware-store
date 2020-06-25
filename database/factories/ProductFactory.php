<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Product::class, function (Faker $faker) {
    $image = UploadedFile::fake()->image('profile.jpg');
    return [
        'title' => 'Jr',
        'price' => 125,
        'information' => $faker->sentence(),
        'image' => $image,
        
    ];
});
