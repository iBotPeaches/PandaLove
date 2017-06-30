<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Onyx\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'google_id'      => $faker->numberBetween(1000, 1000000),
        'email'          => $faker->email,
        'remember_token' => str_random(10),
    ];
});

$factory->define(\Onyx\Calendar\Objects\Event::class, function (Faker\Generator $faker) {
    return [
        'title'       => $faker->sentence(3),
        'type'        => 'competitive',
        'start'       => $faker->dateTime,
        'max_players' => $faker->numberBetween(1, 6),
        'alert_5'     => 0,
        'alert_15'    => 0,
        'game'        => 'overwatch',
    ];
});
