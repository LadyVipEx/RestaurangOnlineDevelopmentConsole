<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

class Database {

	/**
	 * Setup all the illuminate magic 
	 * 
	 * @return $this
	 */
	public function setup()
	{
		$capsule = new Capsule;
		
		$capsule->addConnection([
			'driver' 		=> 'mysql',
			'host'	 		=> getenv('mysql.hostname'),
			'database' 		=> getenv('mysql.database'),
			'username' 		=> getenv('mysql.username'),
			'password' 		=> getenv('mysql.password'),
			'charset'  		=> 'utf8',
			'collation' 	=> 'utf8_unicode_ci',
			'prefix' 		=> ''
		]);

		$capsule->setEventDispatcher(new Dispatcher(new Container));

		$capsule->setAsGlobal();

		$capsule->bootEloquent();

		return $this;
	}
}