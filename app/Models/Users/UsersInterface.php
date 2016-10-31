<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Collection;

interface UsersInterface {

	/**
	 * Set restaurant for client
	 * 
	 * @param  array $restaurant
	 * @return $this
	 */
	public function setRestaurant(array $restaurant);

	/**
	 * Set template for current restaurant
	 * 
	 * @param array $template
	 */
	public function setTemplate(array $template);

	/**
	 * Get restaurants
	 * 
	 * @param  string $term
	 * @return Collection
	 */
	public function getRestaurants($term);

	/**
	 * Get restaurant
	 * 
	 * @param  string $term
	 * @return Collection
	 */
	public function getRestaurant($term);

	/**
	 * Get current user
	 * 
	 * @return Collection
	 */
	public function current();

}