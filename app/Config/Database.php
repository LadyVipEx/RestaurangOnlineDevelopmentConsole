<?php

namespace App\Config;

use Illuminate\Database\Capsule\Manager as Capsule;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

use App\Config\Enviroment;

class Database
{
	/**
	 * @var App\Config\Enviroment
	 */
	protected $enviroment;

	public function __construct()
	{
		$this->enviroment = new Enviroment;
	}

	/**
	 * Setup all the illuminate magic 
	 * 
	 * @return this
	 */
	public function setup()
	{
		$capsule = new Capsule;
		$capsule->addConnection([
			'driver' 		=> 'mysql',
			'host'	 		=> $this->enviroment->get('mysql.hostname'),
			'database' 		=> $this->enviroment->get('mysql.database'),
			'username' 		=> $this->enviroment->get('mysql.username'),
			'password' 		=> $this->enviroment->get('mysql.password'),
			'charset'  		=> 'utf8',
			'collaction' 	=> 'utf8_unicode_ci',
			'prefix' 		=> ''
		]);

		$capsule->setEventDispatcher(new Dispatcher(new Container));

		$capsule->setAsGlobal();

		$capsule->bootEloquent();

		return $this;
	}
}