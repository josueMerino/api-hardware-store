<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Card;
use App\User;
use Faker\Generator as Faker;

$factory->define(Card::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomDigit,
        'card_number' =>$faker->creditCardNumber,
    ];
});
