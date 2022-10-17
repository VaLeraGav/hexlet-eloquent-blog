<?php

namespace App\config\loaders;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Events\Dispatcher;

function bootstrap()
{
    $dbPath = __DIR__ . '/../../db.sqlite';
    touch($dbPath);

    $capsule = new Capsule();
    $capsule->addConnection([
        'driver'    => 'sqlite',
        'database'  => $dbPath,
        /* 'username'  => 'root', */
        /* 'password'  => 'password', */
        /* 'charset'   => 'utf8', */
        /* 'collation' => 'utf8_unicode_ci', */
        /* 'prefix'    => '', */
    ]);
    // Make this Capsule instance available globally via static methods... (optional)
    $capsule->setAsGlobal();

    $capsule->setEventDispatcher(new Dispatcher(new Container()));

    // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
    $capsule->bootEloquent();

    // вывод ошибок
    $capsule->connection()->listen(function ($query) {
        // echo "\n";
        // var_dump($query->sql);
    });

    $faker = \Faker\Factory::create();
    $factory = loadFactories($faker);

    return ['capsule' => $capsule, 'factory' => $factory, 'faker' => $faker];
}

function loadSeeds($factory)
{
    $factory->create(\App\Models\User::class);
    $factory->create(\App\Models\Post::class);
    $factory->create(\App\Models\PostLike::class);
    $factory->create(\App\Models\PostComment::class);
}

function loadFactories($faker)
{
    return Factory::construct($faker, __DIR__ . '/../factories');
}
