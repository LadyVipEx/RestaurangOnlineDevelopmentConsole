<?php

namespace App\Commands\TemplateCommands;

use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor as Templates;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use App\Commands\TemplateCommands\TemplateCommand;

class TemplateAll extends TemplateCommand {
	
	/**
	 * @var App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor
	 */
	protected $templates;

	public function __construct()
	{
		parent::__construct();

		$this->templates = new Templates;
	}

	/**
	 * Set configuration for the command
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('template:all')
			 ->setDescription('Display all available templates');
	}

	/**
	 * Executes terminal command
	 * 
	 * @param  InputInterface  $input  
	 * @param  OutputInterface $output 
	 * @return Terminal output
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->outputTemplateTable(
			$output, 
			$this->templates->all()
		);
	}
}