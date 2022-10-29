<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'author_id' => function () {
            return factory(User::class)->create();
        },
        'name' => str_replace('\'', '', $faker->words(2, true)),
        'description' => str_replace('\'', '', $faker->words(5, true)),
    ];
});
