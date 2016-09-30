<?php

namespace App\Models\RoStatus;

use Illuminate\Database\Eloquent\Model;

use App\Config\Enviroment;
use App\Config\Database;

class RoStatus extends Model {

	/**
	 * @var App\Config\Enviroment
	 */
	protected $enviroment;

	/**
	 * @var App\Config\Database
	 */
	protected $database;

	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'ro_status';

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
	}

	/**
	 * Get status table from client
	 * 
	 * @param  string $clientKey
	 * @return collection
	 */
	public function getStatus($clientKey)
	{
		return $this->where('clientKey', '=', $clientKey)->first();
	}


	/**
	 * Set status for client
	 * 
	 * @param  boolean $status [description]
	 * @return this
	 */
	public function setStatus($clientKey, $status)
	{
		$this->where('clientKey', '=', $clientKey)->update([
			'online' => $status
		]);

		return $this;
	}

	/**
	 * Set client online
	 * 
	 * @param  string $clientKey
	 * @return this
	 */
	public function setOnline($clientKey)
	{
		return $this->setStatus($clientKey, true);
	}

	/**
	 * Set client offline
	 * 
	 * @param  string $clientKey
	 * @return this
	 */
	public function setOffline($clientKey)
	{
		return $this->setStatus($clientKey, false);
	}
}