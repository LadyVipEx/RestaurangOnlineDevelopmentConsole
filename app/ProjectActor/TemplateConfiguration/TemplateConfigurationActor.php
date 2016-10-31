<?php

namespace App\ProjectActor\TemplateConfiguration;

use Illuminate\Filesystem\Filesystem;
use App\ProjectActor\AbstractActor;

class TemplateConfigurationActor extends AbstractActor implements TemplateConfigurationInterface {

	/**
	 * File content off fileconfiguration
	 * 
	 * @var array
	 */
	protected $fileContent;

	/**
	 * Templates
	 * 
	 * @var array
	 */
	protected $templates;

	/**
	 * Illuminate filesystem
	 * 
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->filesystem = new Filesystem;

		$this->fileContent = explode("\n", 
			$this->configurationFileContent()
		);

		$this->setTemplates();
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
		$template = $this->search($template);

		return reset($template);
	}

	/**
	 * Add a template to the configuration file
	 * 
	 * @param string $name
	 * @param string $template
	 * @param string $baseTemplate
	 * @param string $globalTemplate
	 * @param string $iconFolder
	 * @return this
	 */
	public function add($name, $template, $baseTemplate, $globalTemplate, $iconFolder)
	{
		$find = '/* -- Automated Additions -- */';

		$fileContent = $this->doFilters([
			'filterExplodeNewLines'
		], $this->configurationFileContent());

		$found = false;
		foreach ($fileContent as $index => $line) 
		{
			if (strpos($line, $find) !== false && $found === false)
			{
				$found = true;
			}

			if ($found === true && strlen($line) === 0)
			{
				array_splice($fileContent, $index, 0, '$collection->addTemplate(\'' . $name . '\', \'' . $template . '\', \'' . $baseTemplate . '\', \'' . $globalTemplate . '\', \'' . $iconFolder . '\');'); 

				break;
			}
		}

		$this->filesystem->put(
			$this->getTemplateConfigurationPath(), join("\n", $fileContent)
		);

		return $this;
	}

	/**
	 * Duplicate a template configuration
	 * 
	 * @param  string $existingTemplate
	 * @param  string $template
	 * @return $this
	 */
	public function duplicate($existingTemplate, $template) {
		$existingTemplate = $this->first($existingTemplate);

		$this->filesystem->copyDirectory(
			$this->getProjectPath() . $this->translateGlobalTemplate($existingTemplate['globalTemplate']) . '/restaurants/' . $existingTemplate['template'],
			$this->getProjectPath() . $this->translateGlobalTemplate($existingTemplate['globalTemplate']) . '/restaurants/' . $template
		);

		$this->filesystem->copyDirectory(
			$this->getProjectPath() . 'public/application/views/' . $existingTemplate['template'],
			$this->getProjectPath() . 'public/application/views/' . $template
		);

		$this->add(
			$template, 
			$template, 
			$existingTemplate['baseTemplate'], 
			$existingTemplate['globalTemplate'], 
			$existingTemplate['iconFolder']
		);

		return $this;
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
	 * Search through the templates for given term
	 * 
	 * @param  string $searchTerm
	 * @return array
	 */
	protected function search($searchTerm)
	{
		$results = [];
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
	 * Get format of the template configuration
	 * 
	 * @param  array $array
	 * @return array
	 */
	protected function formatTemplateInformation($array)
	{
		return [
			'name' 			 => $array[0],
			'template' 		 => $array[1],
			'baseTemplate' 	 => $array[2],
			'globalTemplate' => $array[3],
			'iconFolder'	 => $array[4]
		];
	}

	/**
	 * Filter the configuration file for 
	 * 
	 * @param  string $configurationFileContent
	 * @return array
	 */
	protected function filterConfiguration($configurationFileContent)
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
	protected function configurationFileContent()
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
	protected function loadTemplates()
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
	protected function doFilters(array $filters, $filterObject)
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
	protected function filterTemplateRows($fileContent)
	{
		$fileContent = explode("\n", $fileContent);

		return array_filter($fileContent, function($string) {

			if (strpos($string, '$collection->addTemplate') !== false)
			{
				return $string;
			}
		});
	}

	public function filterExplodeNewLines($string)
	{
		return explode("\n", $string);
	}

	/**
	 * Remove all but the content between <(> <)>
	 * 
	 * @param  array $string [description]
	 * @return array
	 */
	protected function filterArrayContentBetweenParenthesis($array)
	{
		foreach ($array as $template) 
		{
			preg_match('/[(](.*)[)]/', $template, $a);

			$content[] = $a[1];
		}

		return $content;
	}

	/**
	 * Remove apostrophes foreach in array
	 * 
	 * @param  array $array
	 * @return array
	 */
	protected function filterRemoveApostrophes($array)
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
	protected function filterRemoveSemicolons($array)
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
	protected function filterExplodeTemplateInformation(array $array)
	{
		for ( $i = 0; $i < count($array); $i++ ) 
		{
			$r = array_map(
				'trim', explode(' ', $array[$i])
			);

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
	protected function sortToTemplatesRow($array)
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
	protected function cleanArray($array)
	{
		$array = array_filter($array, function($value) {
			return !empty($value) || $value === 0;
		});

		return $array;
	}
}