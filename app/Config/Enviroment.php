<?php

namespace App\Config;

use Illuminate\Filesystem\Filesystem;

class Enviroment
{

	/**
	 * @var Illuminate\Filesystem\Filesystem;
	 */
	protected $filesystem;

	/**
	 * Array of config variables from 
	 * enviroment file
	 * 
	 * @var array
	 */
	protected $config;

	/**
	 * Path to enviroment file
	 * 
	 * @var string
	 */
	protected $path;

	/**
	 * @param string $path alternate way to set file path
	 */
	public function __construct($path = null)
	{
		$this->path = isset($path) ? $path : __DIR__ . '/../../.env';

		$this->filesystem = new Filesystem;

		$this->config = $this->collect();
	}

	/**
	 * Get a enviroment variable value by key 
	 * 
	 * @param  string $variable
	 * @return string
	 */
	public function get($variable)
	{
		return $this->config[$variable];
	}

	/**
	 * Collects the enviroment file
	 * 
	 * @return array
	 */
	protected function collect()
	{
		$enviroment = $this->filesystem->get(
			$this->getPath()
		);

		$enviroment = $this->explodeNewLines($enviroment);
		$enviroment = $this->explodeEqualAndSort($enviroment);

		return $enviroment;
	}

	/**
	 * Explode each item in array by equal sign.
	 * Then collect into a build array
	 * 
	 * @param  array  $array
	 * @return array
	 */
	protected function explodeEqualAndSort(array $array)
	{
		foreach ($array as $enviromentLine) 
		{
			$enviromentParts = explode("=", $enviromentLine);

			$build[trim($enviromentParts[0])] = trim($enviromentParts[1]);
		}

		return $build;
	}

	/**
	 * Explode string by new lines
	 * 
	 * @param  string $string
	 * @return array
	 */
	protected function explodeNewLines($string)
	{
		return explode("\n", $string);
	}

	/**
	 * Getter for path
	 * 
	 * @return string
	 */
	protected function getPath()
	{
		return $this->path;
	}	
}