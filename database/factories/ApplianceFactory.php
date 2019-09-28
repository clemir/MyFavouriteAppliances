<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Appliance;
use Faker\Generator as Faker;

$factory->define(Appliance::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'price' => $faker->numberBetween($min = 1000, $max = 999999),
        'model' => $faker->asciify('******'),
        'url' => $faker->url,
        'image' => $faker->imageUrl(250, 250),
        'status' => true,
        'description' => $faker->sentences(3)
    ];
});
