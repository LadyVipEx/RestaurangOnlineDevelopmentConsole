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
	protected $users;

	public function __construct()
	{
		parent::__construct();

		$this->users = new Users;

		$this->templates = new TemplateConfigurationActor;

		$this->environment = new EnvironmentConfigurationActor;
	}

	public function setTemplate($template)
	{
		$this->users->setTemplate($template['name']);
		$this->environment->setTemplate($template['globalTemplate'], $template['name']);
	}

	public function current()
	{
		$current = $this->users->current()->toArray();

		foreach ($this->environment->getConfiguration() as $globalTemplate => $template)
		{
			$current[$globalTemplate] = $template[0];
		}

		return $current;
	}

	public function searchRestaurants($name)
	{
		return $this->users->searchRestaurants($name)->toArray();
	}

	public function searchRestaurant($name)
	{
		return $this->users->searchRestaurant($name);
	}

	public function setRestaurant($restaurant)
	{
		$this->users->setRestaurant($restaurant['clientKey']);
		$template = $this->searchTemplates($restaurant['webTemplateId']);
		$this->setTemplate(reset($template));
	}

	public function searchTemplates($name)
	{
		return $this->templates->search($name);
	}

	/**
	 * Alias of searchRestaurant
	 * 
	 * @param  string $name
	 * @return array
	 */
	public function search($name)
	{
		return $this->templates->search($name);
	}
}