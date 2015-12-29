<?php

namespace App\Models;

interface UsersInterface {

	/**
	 * Set restaurant for client
	 * 
	 * @param  array $restaurant
	 * @return this
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
	 * @return collection
	 */
	public function getRestaurants($term);

	/**
	 * Get restaurant
	 * 
	 * @param  string $term
	 * @return collection
	 */
	public function getRestaurant($term);

	/**
	 * Get current user
	 * 
	 * @return collection
	 */
	public function current();

}