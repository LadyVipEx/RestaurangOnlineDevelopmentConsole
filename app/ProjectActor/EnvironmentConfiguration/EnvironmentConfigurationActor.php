<?php

namespace App\ProjectActor\EnvironmentConfiguration;

use Illuminate\Filesystem\Filesystem;
use App\ProjectActor\AbstractActor;

class EnvironmentConfigurationActor extends AbstractActor {

	protected $configuration;

	protected $filesystem;

	public function __construct()
	{
		parent::__construct();

		$this->filesystem = new Filesystem;

		$this->setConfiguration(
			$this->loadConfiguration()
		);
	}

	public function setTemplate($template)
	{
		$globalTemplate = $this->translateGlobalTemplate($template['globalTemplate']);

		$build = [];

		foreach ($this->globalTemplates() as $global) 
		{
			if ($globalTemplate == $global)
			{
				$build[$global] = [$template['name']];
			}
			else
			{
				$build[$global] = ['nuthin'];
			}
		}
		
		$this->setConfiguration($build)->write($build);
	}

	public function translateGlobalTemplate($name)
	{
		switch ($name)
		{
			case 'global':
					return 'assets';
				break;
			case 'globalbs3';
					return 'assetsBS3';
				break;
		}
	}

	public function translateEnviroment($name)
	{
		switch ($name)
		{
			case 'assets':
					return 'global';
				break;
			case 'assetsBS3';
					return 'globalbs3';
				break;
		}
	}

	/**
	 * All available types of templates according to
	 * our grunt file in project
	 * 
	 * @return array
	 */
	public function globalTemplates()
	{
		return [
			'assets',
			'assetsBS3'
		];
	}

	/**
	 * Writes configuration in class to file
	 * 
	 * @return void
	 */
	public function write()
	{
		$configuration = json_encode($this->configuration);

		$this->filesystem->put(
			$this->getTemplateEnviromentPath(),
			json_encode($this->configuration, JSON_PRETTY_PRINT)
		);
	}

	/**
	 * Read configuration from live file
	 * 
	 * @return array
	 */
	public function loadConfiguration()
	{
		return json_decode(
			$this->getFileConfig()
		, true);
	}

	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;

		return $this;
	}

	public function getConfiguration()
	{
		$this->setConfiguration(
			$this->loadConfiguration()
		);
		
		return $this->configuration;
	}

	public function getTranslatedConfiguration()
	{
		foreach ($this->configuration as $globalTemplate => $template)
		{
			$array[$this->translateEnviroment($globalTemplate)] = $template;
		}

		return $array;
	}

	public function getFileConfig()
	{
		return $this->filesystem->get(
			$this->getTemplateEnviromentPath()
		);
	}

}