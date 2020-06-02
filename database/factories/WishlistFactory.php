<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wishlist;
use Faker\Generator as Faker;

$factory->define(Wishlist::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'number_of_items' => $faker->randomDigit,
    ];
});
