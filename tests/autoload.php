<?php

use Illuminate\Database\Capsule\Manager as Capsule;

include __DIR__.'/../vendor/autoload.php';

$capsule = new Capsule();

//Adds MongoDb support.
$capsule->getDatabaseManager()->extend('mongodb', function ($config) {
    return new Jenssegers\Mongodb\Connection($config);
});

//Create connection
$capsule->addConnection([
        'driver' => 'mongodb',
        'host' => 'localhost',
        'port' => 27017,
        'database' => 'default',
        'username' => '',
        'password' => '',
        'options' => [
            'db' => 'admin',
        ],
    ],
    'default'
);
$capsule->bootEloquent();
$capsule->setAsGlobal();
