<?php

use Faker\Generator as Faker;

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

$factory->define(\App\Models\PostComment::class, function (Faker $faker) {
    return [
        'body' => $faker->text,
        'post_id' => function () {
            return \App\Models\Post::first()->id;
        },
        'creator_id' => function () {
            return \App\Models\User::first()->id;
        },
    ];
});
