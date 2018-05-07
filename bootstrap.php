<?php

require __DIR__ . '/vendor/autoload.php';

error_reporting(-1);

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required([
    'database.hostname',
    'database.username',
    'database.password',
    'database.database'
]);

$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection([
    'driver' 		=> 'mysql',
    'host'	 		=> getenv('database.hostname'),
    'database' 		=> getenv('database.database'),
    'username' 		=> getenv('database.username'),
    'password' 		=> getenv('database.password'),
    'charset'  		=> 'utf8',
    'collation' 	=> 'utf8_unicode_ci',
    'prefix' 		=> ''
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
