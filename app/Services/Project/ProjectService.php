<?php

namespace App\Services\Project;

use App\ProjectActor\EnvironmentConfiguration\EnvironmentConfigurationActor;
use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor;
use App\Services\AbstractService;
use App\Models\Users;

class ProjectService extends AbstractService {

	/**
	 * @var App\ProjectActor\EnvironmentConfiguration\EnvironmentConfigurationActor
	 */
	protected $environment;

	/**
	 * @var App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor
	 */
	protected $templates;

	/**
	 * @var App\Models\Users
	 */
	protected $restaurants;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->environment = new EnvironmentConfigurationActor;

		$this->templates = new TemplateConfigurationActor;

		$this->restaurants = new Users;

		parent::__construct();
	}


	/**
	 * Get all templates from configuration
	 * 
	 * @return array
	 */
	public function allTemplates()
	{
		return $this->templates->all();
	}

	/**
	 * Get template from configuration file 
	 * 
	 * @param  string $term
	 * @return array
	 */
	public function getTemplate($term)
	{
		return $this->templates->first($term);
	}

	/**
	 * Get template from configuration file
	 * 
	 * @param  string $term
	 * @return array
	 */
	public function getTemplates($term)
	{
		return $this->templates->get($term);
	}

	/**
	 * Set template for current user in database
	 * 
	 * @param  array $template
	 * @return this
	 */
	public function setTemplate($template)
	{
		$this->restaurants->setTemplate($template);

		return $this;
	}

	/**
	 * Get all restaurants from database
	 * 
	 * @return array
	 */
	public function allRestaurants()
	{
		return $this->restaurants->all()
								 ->toArray();
	}

	/**
	 * Get restaurant from database
	 * 
	 * @param  string $term
	 * @return array
	 */
	public function getRestaurant($term)
	{
		return $this->restaurants->getRestaurant($term)
								 ->toArray();
	}

	/**
	 * Get restaurants from database by value
	 * 
	 * @param  string $term
	 * @return array
	 */
	public function getRestaurants($term)
	{
		return $this->restaurants->getRestaurants($term)
								 ->toArray();
	}

	/**
	 * Set restaurant for client
	 * 
	 * @param  array $restaurant
	 * @return this
	 */
	public function setRestaurant($restaurant)
	{
		$this->restaurants->setRestaurant($restaurant);

		return $this;
	}

	/**
	 * Set template to grunt
	 * 
	 * @param  array $template
	 * @return this
	 */
	public function grunt($template)
	{
		$this->environment->setTemplate($template);

		return $this;
	}

	/**
	 * Get current information 
	 * 
	 * @return array
	 */
	public function current()
	{
		$current = $this->restaurants->current()->toArray();

		foreach ($this->environment->getConfiguration() as $globalTemplate => $template)
		{
			$current[$globalTemplate] = $template[0];
		}

		return $current;
	}
}