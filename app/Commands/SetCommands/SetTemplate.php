<?php

namespace App\Commands\SetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

use App\Services\Project\ProjectService;

class SetTemplate extends SetCommand {

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
	 * Set configuration for set:template
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('set:template')
			 ->setDescription('Set current template')
			 ->addArgument('term', InputArgument::REQUIRED, 'Enter template name');
	}


	/**
	 * Execute set:template
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$results = $this->project->getTemplates(
			$input->getArgument('term')
		);

		$this->prependSelector()->outputTemplateTable($output, $results);

		$answer = $this->getHelper('question')->ask($input, $output,
			new Question('<info>Select by index: </info><comment>#</comment>', '0')
		);

		$this->project->setTemplate($results[$answer])
					  ->grunt($results[$answer]);
	}
}