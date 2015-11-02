<?php

namespace App\Commands\AllCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use App\Services\Project\ProjectService;

class AllTemplates extends AllCommand {

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
	 * Set configuration for all:templates
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('all:templates')
			 ->setDescription('Lists all templates in configuration');
	}


	/**
	 * Execute all:templates
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->outputTemplateTable($output,
			$this->project->allTemplates()
		);
	}
}