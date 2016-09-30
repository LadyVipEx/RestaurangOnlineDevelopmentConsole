<?php

namespace App\ProjectActor;

use App\Config\Enviroment;

class AbstractActor {

	/**
	 * @var Project/public/application/config/templates.php
	 */
	protected $templateConfigurationPath;

	/**
	 * @var Project/.env.local.js
	 */
	protected $templateEnviromentPath;

	/**
	 * @var Project/
	 */
	protected $projectPath;

	/**
	 * @var App\Config\Enviroment
	 */
	protected $enviroment;

	public function __construct()
	{
		$this->enviroment = new Enviroment;

		$this->setProjectPath(
			$this->enviroment->get('templates.ro.se')
		);

		$this->setTemplateConfigurationPath();

		$this->setTemplateEnviromentPath();
	}

	/**
	 * Set the path for config/templates.php in project
	 * 	
	 * @param string $path
	 */
	protected function setTemplateConfigurationPath($path = null)
	{
		$projectPath = !is_null($path) ? $path : $this->projectPath;

		$this->templateConfigurationPath = $this->projectPath . 'public/application/config/templates.php';
	}

	/**
	 * Set the path for enviroment file in project
	 * 	
	 * @param string $path
	 */
	protected function setTemplateEnviromentPath($path = null)
	{
		$projectPath = !is_null($path) ? $path : $this->projectPath;

		$this->templateEnviromentPath = $this->projectPath . '.env.local.json';
	}

	/**
	 * Set the path for project
	 * 
	 * @param string $path
	 */
	protected function setProjectPath($path)
	{
		$this->projectPath = $path;
	}

	/**
	 * Set the path for config/templates.php in project
	 * 
	 * @return string
	 */
	public function getTemplateConfigurationPath()
	{
		return $this->templateConfigurationPath;
	}

	/**
	 * Get the path for enviroment file in project
	 * 	
	 * @return string
	 */
	public function getTemplateEnviromentPath()
	{
		return $this->templateEnviromentPath;
	}

	/**
	 * get the path for project
	 * 
	 * @return string 
	 */
	public function getProjectPath()
	{
		return $this->projectPath;
	}
}