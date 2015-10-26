<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
	'driver' 		=> 'mysql',
	'host'	 		=> env('mysql.hostname'),
	'database' 		=> env('mysql.database'),
	'username' 		=> env('mysql.username'),
	'password' 		=> env('mysql.password'),
	'charset'  		=> 'utf8',
	'collaction' 	=> 'utf8_unicode_ci',
	'prefix' 		=> ''
]);

// Set the event dispatcher used by Eloquent models... 
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods...
$capsule->setAsGlobal();

// Setup the Eloquent ORM...
$capsule->bootEloquent();