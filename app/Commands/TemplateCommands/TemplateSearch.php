<?php 

namespace App\Commands\TemplateCommands;

use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor as Templates;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Commands\TemplateCommands\TemplateCommand;

class TemplateSearch extends TemplateCommand {
	
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
		$this->setName('template:search')
			 ->setDescription('Search for template')
			 ->addArgument('name', InputArgument::REQUIRED, 'name');
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
		$results = $this->templates->search(
			$input->getArgument('name')
		);
		
		if (count($results) == 0) 
		{
			$output->writeln('<fg=red>Nothing found</>'); 
			die();
		}

		$this->outputTemplateTable($output, $results);
	}
}
