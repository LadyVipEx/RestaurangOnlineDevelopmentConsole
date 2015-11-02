<?php

namespace App\ProjectActor\TemplateConfiguration;

use Illuminate\Filesystem\Filesystem;
use App\ProjectActor\AbstractActor;

class TemplateConfigurationActor extends AbstractActor {

	/**
	 * Templates
	 * 
	 * @var array
	 */
	protected $templates;

	/**
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * File content off fileconfiguration
	 * 
	 * @var array
	 */
	protected $configuration;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->filesystem = new Filesystem;

		$this->setTemplates();
	}

	/**
	 * Getter for templates
	 * 
	 * @return array
	 */
	public function getTemplates()
	{
		return $this->templates;
	}

	/**
	 * Setter for templates 
	 * 
	 * @param array $templates
	 */
	protected function setTemplates(array $templates = null)
	{
		$templates = $templates ? $templates : $this->loadTemplates();

		$this->templates = $templates;
	}

	/**
	 * Get all the templates in configuration file
	 * 
	 * @return array
	 */
	public function all()
	{
		return $this->templates;
	}

	/**
	 * Search through the templates for given term
	 * 
	 * @param  string $searchTerm
	 * @return array
	 */
	public function search($searchTerm)
	{
		$i = 0;

		foreach ($this->templates as $index => $template) 
		{
			if (strpos($template['name'], $searchTerm) !== false)
			{
				$results[$i] = $template;
				
				$i++;
			}
		}

		return $results;
	}

	/**
	 * Get templates by template name
	 * 
	 * @param  string $template
	 * @return array
	 */
	public function get($template)
	{
		return $this->search($template);
	}

	/**
	 * Get the first template by template name
	 * 
	 * @param  string $template
	 * @return array
	 */
	public function first($template)
	{
		return reset(
			$this->search($template)
		);
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
	public function filterConfiguration($configurationFileContent)
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

	/**
	 * Load the configuration as a clean array
	 * 
	 * @return array
	 */
	public function loadTemplates()
	{
		$templates = $this->filterConfiguration(
			$this->configurationFileContent()
		);

		return $templates;
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

			$sorted[$key] = $this->formatTemplateInformation(
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
}