<?php

namespace App\Commands\DuplicateCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

use App\Services\Project\ProjectService;

class DuplicateTemplate extends DuplicateCommand {

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
	 * Set configuration for duplicate:template
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('duplicate:template')
			 ->setDescription('Duplicate current template')
			 ->addArgument('existingTemplate', InputArgument::REQUIRED, 'Enter existing template name')
			 ->addArgument('newTemplate', InputArgument::REQUIRED, 'Enter new template name');
	}


	/**
	 * Execute duplicate:template
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$results = $this->project->getTemplates(
			$input->getArgument('existingTemplate')
		);

		$this->prependSelector()->outputTemplateTable($output, $results);

		$answer = $this->getHelper('question')->ask($input, $output,
			new Question('<info>Select by index: </info><comment>#</comment>', '0')
		);

		$this->project->duplicateTemplate($results[$answer]['template'], $input->getArgument('newTemplate'))
						->setTemplate(['name' => $input->getArgument('newTemplate')])
						->grunt($input->getArgument('newTemplate'));

		$this->outputCurrentTable($output,
			$this->project->current()
		);
	}
}