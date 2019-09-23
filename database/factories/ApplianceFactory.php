<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Appliance;
use Faker\Generator as Faker;

$factory->define(Appliance::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'price' => $faker->randomNumber,
        'brand' => $faker->company,
        'type' => $faker->randomElement(['small appliances', 'dishwasher']),
    ];
});
