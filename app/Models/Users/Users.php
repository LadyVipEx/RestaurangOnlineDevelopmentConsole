<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\UsersInterface;
use App\Config\Enviroment;
use App\Config\Database;

class Users extends Model implements UsersInterface {

	/**
	 * @var App\Config\Enviroment
	 */
	protected $enviroment;

	/**
	 * @var App\Config\Database
	 */
	protected $database;

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

		(new Database)->setup();

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
			'webURL' => $this->getClient()
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
		$this->where('webURL', $this->getClient())->update([
			'webTemplateId' => $template['name']
		]);

		return $this;
	}

	/**
	 * Toggle entity to true or false
	 * 
	 * @param  mixed  $entity
	 * @param  string $identifier
	 * @return this
	 */
	protected function toggleEntity($entity, $identifier)
	{
		$changeUser = $this->where(
			$this->clientkeyOrRestaurantName($identifier)
		, $identifier)->first();

		$this->where(
			$this->clientkeyOrRestaurantName($identifier)
		, $identifier)->update([
			$entity => $changeUser->{ $entity } == false ? true : false
		]);		

		return $this;
	}

	/**
	 * Toggle popup for restaurant
	 * 
	 * @param  mixed $identifier
	 * @return this
	 */
	public function togglePopup($identifier = null)
	{
		$identifier = $identifier === null ? $this->current()->clientKey : $identifier;
		
		return $this->toggleEntity('featurePopup', $identifier);
	}

	/**
	 * Remove current client from restaruant
	 * 
	 * @return this
	 */
	protected function removeClient()
	{
		$this->where('webURL', $this->getClient())->update([
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
		return $this->where('webURL', '=', $this->getClient())->first();
	}

	/**
	 * Set required if non is set
	 *
	 * @return this
	 */
	protected function setRequired()
	{
		if ($this->where('webURL', '=', $this->getClient())->count() == 0)
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
		$this->where('clientkey', $this->enviroment->get('default.clientkey'))->update([
			'webURL' => $this->getClient(),
			'webTemplateId' => $this->enviroment->get('default.template')
		]);

		return $this;
	}

	/**
	 * Getter for current client
	 * 
	 * @return string
	 */
	public function getClient()
	{
		return $this->enviroment->get('default.client');	
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