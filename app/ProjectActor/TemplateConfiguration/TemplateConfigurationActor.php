<?php

namespace App\ProjectActor\TemplateConfiguration;

use Illuminate\Filesystem\Filesystem;
use App\ProjectActor\AbstractActor;

class TemplateConfigurationActor extends AbstractActor {

	/**
	 * Template configuration
	 * 
	 * @var array
	 */
	protected $configuration;

	/**
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;
	

	public function __construct()
	{
		parent::__construct();

		$this->filesystem = new Filesystem;

		$this->configuration = $this->loadConfiguration();
	}

	/**
	 * Getter for configuration
	 * 
	 * @return array
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * Get all the templates in configuration file
	 * 
	 * @return array
	 */
	public function all()
	{
		return $this->configuration;
	}

	/**
	 * Search through the templates for given term
	 * 
	 * @param  string $searchTerm
	 * @return array
	 */
	public function search($searchTerm)
	{
		foreach ($this->configuration as $template => $information) 
		{
			if (strpos($template, $searchTerm) !== false)
			{
				$results[$template] = $information;
			}
		}

		return $results;
	}

	/**
	 * Get format of the template configuration
	 * 
	 * @param  array $array
	 * @return array
	 */
	public function formatTemplateInformation($array)
	{
		return [
			'name' 				=> $array[0],
			'template' 			=> $array[1],
			'baseTemplate' 		=> $array[2],
			'globalTemplate' 	=> $array[3],
			'iconFolder'		=> $array[4]
		];
	}

	/**
	 * Filter the configuration file for 
	 * 
	 * @param  string $configurationFileContent
	 * @return array
	 */
	public function filterTemplates($configurationFileContent)
	{
		return $this->doFilters([
			'filterTemplateRows',
			'filterArrayContentBetweenParenthesis',
			'filterRemoveApostrophes',
			'filterRemoveSemicolons',
			'filterExplodeTemplateInformation',
			'cleanArray',
			'sortToTemplatesRow',
		], $configurationFileContent);
	}

	/**
	 * Load the configuration as a clean array
	 * 
	 * @return array
	 */
	public function loadConfiguration()
	{
		$templates = $this->filterTemplates(
			$this->configurationFileContent()
		);

		return $templates;
	}

	/**
	 * Keep rows whith addTemplate function
	 * 
	 * @param  string $fileContent
	 * @return string
	 */
	public function filterTemplateRows($fileContent)
	{
		$fileContent = explode("\n", $fileContent);

		return array_filter($fileContent, function($string) {

			if (strpos($string, '$collection->addTemplate') !== false)
			{
				return $string;
			}
		});
	}

	/**
	 * Remove all but the content between <(> <)>
	 * 
	 * @param  array $string [description]
	 * @return array
	 */
	public function filterArrayContentBetweenParenthesis($array)
	{
		for ( $i = 0; $i < count($array); $i++ ) 
		{
			$a = split('[()]', $array[$i]);

			$array[$i] = $a[1];	
		}

		return $array;
	}

	/**
	 * Remove apostrophes foreach in array
	 * 
	 * @param  array $array
	 * @return array
	 */
	public function filterRemoveApostrophes($array)
	{
		for ( $i = 0; $i < count($array); $i++ ) 
		{
			$array[$i] = str_replace('\'', '', $array[$i]);	
		}

		return $array;
	}

	/**
	 * Remove semicolons foreach in array
	 * 
	 * @param  array $array
	 * @return array
	 */
	public function filterRemoveSemicolons($array)
	{
		for ( $i = 0; $i < count($array); $i++ ) 
		{
			$array[$i] = str_replace(',', '', $array[$i]);	
		}

		return $array;
	}

	/**
	 * Do filters
	 * 
	 * @param  array  $filters
	 * @param  string $filterObject
	 * @return filtered filterObject
	 */
	public function doFilters(array $filters, $filterObject)
	{
		foreach ($filters as $filter) 
		{
			$filterObject = $this->{ $filter }($filterObject); 
		}

		return $filterObject;
	}

	/**
	 * Explode alla array's by spaces
	 * 
	 * @param  array  $array
	 * @return array
	 */
	public function filterExplodeTemplateInformation(array $array)
	{
		for ( $i = 0; $i < count($array); $i++ ) 
		{
			$r = explode(' ', $array[$i]);

			$array[$i] = $this->cleanArray($r);
		}

		return $array;
	}

	/**
	 * Sort to appropriate format
	 * 
	 * @param  array $array
	 * @return array
	 */
	public function sortToTemplatesRow($array)
	{
		$sorted = [];

		foreach ($array as $key => $values) 
		{
			$value 	= [];
			$i 		= 0;

			foreach ($values as $val) 
			{
				$value[$i] = $val;

				$i++;
			}

			$sorted[$value[0]] = $this->formatTemplateInformation(
				array_map('trim', $value)
			);
		}

		return $sorted;
	}

	/**
	 * Clean all empty arrays in array
	 * 
	 * @param  array $array
	 * @return array
	 */
	public function cleanArray($array)
	{
		$array = array_filter($array, function($value) {
			return !empty($value) || $value === 0;
		});

		return $array;
	}

	/**
	 * Reads the content of the configuration files
	 * 
	 * @return string
	 */
	public function configurationFileContent()
	{
		return $this->filesystem->get(
			$this->getTemplateConfigurationPath()
		);
	}
}