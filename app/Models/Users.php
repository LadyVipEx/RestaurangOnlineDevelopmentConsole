<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UsersInterface;
use App\Config\Enviroment;

class Users extends Model implements UsersInterface {

	/**
	 * @var App\Config\Enviroment
	 */
	protected $enviroment;

	/**
	 * Default host to set for template
	 * 
	 * @var string
	 */
	protected $client;

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

		$this->enviroment = new Enviroment;

		$this->client = $this->enviroment->get('default.client');

		$this->setRequired();
	}

	/**
	 * Get restaurants
	 * 
	 * @param  string $term
	 * @return collection
	 */
	public function getRestaurants($term)
	{
		$key = $this->clientkeyOrRestaurantName($term);

		return $this->where($key, 'LIKE', '%' . $term . '%')->get();
	}

	/**
	 * Get restaurant
	 * 
	 * @param  string $term
	 * @return collection
	 */
	public function getRestaurant($term)
	{
		$key = $this->clientkeyOrRestaurantName($term);

		return $this->where($key, 'LIKE', '%' . $term . '%')->first();
	}

	/**
	 * Set restaurant for client
	 * 
	 * @param  array $restaurant
	 * @return this
	 */
	public function setRestaurant(array $restaurant)
	{
		$this->removeClient();

		$this->where('clientKey', '=', $restaurant['clientKey'])->update([
			'webURL' => $this->client
		]);

		return $this;
	}

	/**
	 * Set template for current restaurant
	 * 
	 * @param array $template
	 */
	public function setTemplate(array $template)
	{
		$this->where('webURL', $this->client)->update([
			'webTemplateId' => $template['name']
		]);

		return $this;
	}

	/**
	 * Remove current client from restaruant
	 * 
	 * @return this
	 */
	protected function removeClient()
	{
		$this->where('webURL', $this->client)->update([
			'webURL' => ''
		]);

		return $this;
	}

	/**
	 * Get current user
	 * 
	 * @return collection
	 */
	public function current()
	{
		return $this->where('webURL', '=', $this->client)->first();
	}

	/**
	 * Set required if non is set
	 *
	 * @return this
	 */
	protected function setRequired()
	{
		if ($this->where('webURL', '=', $this->client)->count() == 0)
		{
			$this->desireDefault();
		}

		return $this;
	}

	/**
	 * Update database with defaults
	 * 
	 * @return this
	 */
	protected function desireDefault()
	{
		$this->where('clientkey', env('default.clientkey'))->update([
			'webURL' => $this->client,
			'webTemplateId' => env('default.template')
		]);

		return $this;
	}

	/**
	 * Get value kind. Clienykey || restaurantName
	 *  
	 * @param  string $value
	 * @return string
	 */
	protected function clientkeyOrRestaurantName($value)
	{
		return strlen($value) === 18 ? 'clientkey' : 'restaurantName' ;
	}
}