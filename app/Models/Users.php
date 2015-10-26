<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {

	/**
	 * Default host to set for template
	 * 
	 * @var string
	 */
	protected $userHost;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'users';

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
	public $timestamps = false;


	public function __construct()
	{
		parent::__construct();

		$this->userHost = env('default.host');

		$this->setDefaultsIfNeeded();
	}

	public function setDefaultsIfNeeded()
	{
		if ($this->where('webURL', '=', $this->userHost)->count() == 0)
		{
			$this->setDefaults();
		}
	}

	public function setDefaults()
	{
		$this->where('clientkey', env('default.clientkey'))
			 ->update([
			 		'webURL' => $this->userHost,
			 		'webTemplateId' => env('default.template')
			 	]);
	}

	public function setTemplate($template)
	{
		$this->where('webURL', $this->userHost)
			 ->update([
			 	'webTemplateId' => $template
			 ]);
	}

	public function searchRestaurant($value)
	{
		$key = $this->clientkeyOrRestaurantName($value);

		return $this->where($key, 'LIKE', '%' . $value . '%')->first();
	}

	public function searchRestaurants($value)
	{
		$key = $this->clientkeyOrRestaurantName($value);

		return $this->where($key, 'LIKE', '%' . $value . '%')->get();
	}

	public function setRestaurant($value)
	{
		$key = $this->clientkeyOrRestaurantName($value);

		$this->resetSelectedData();

		return $this->where($key, 'LIKE', '%' . $value . '%')
					->update([
						'webURL' => $this->userHost
					]);
	}

	public function resetSelectedData()
	{
		$this->where('webURL', $this->userHost)
			 ->update([
			 	'webURL' => ''
			 ]);
	}

	public function clientkeyOrRestaurantName($value)
	{
		return strlen($value) === 18 ? 'clientkey' : 'restaurantName' ;
	}

	public function current()
	{
		return $this->where('webURL', '=', $this->userHost)->first();
	}		
}