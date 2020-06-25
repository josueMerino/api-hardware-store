<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StockProduct;
use Faker\Generator as Faker;

$factory->define(StockProduct::class, function (Faker $faker) {
    return [
        'product_id' => 1,
        'number_of_items' => 12,
    ];
});
