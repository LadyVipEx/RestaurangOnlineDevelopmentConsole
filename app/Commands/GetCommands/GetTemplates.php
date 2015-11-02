<?php

namespace App\Commands\GetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\Services\Project\ProjectService;

class GetTemplates extends GetCommand {

	/**
	 * @var App\Services\Project\ProjectService
	 */
	protected $project;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->project = new ProjectService;
	}

	/**
	 * Set configuration for get:templates
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('get:templates')
			 ->setDescription('Lists all templates in configuration by argument')
			 ->addArgument('term', InputArgument::REQUIRED, 'Enter search term');
	}


	/**
	 * Execute get:templates
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$term = $input->getArgument('term');

		$this->outputTemplateTable($output,
			$this->project->getTemplates($term)
		);
	}
}