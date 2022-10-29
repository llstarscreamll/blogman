<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => str_replace('\'', '', $faker->firstName()),
        'last_name' => str_replace('\'', '', $faker->lastName()),
        'email' => $faker->unique()->safeEmail,
        'type' => User::BLOGGER_TYPE,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'last_login' => now(),
    ];
});
