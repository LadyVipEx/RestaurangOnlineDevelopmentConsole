<?php

namespace App\Commands\SetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;

use App\Services\Project\ProjectService;

class SetRestaurant extends SetCommand {

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
	 * Set configuration for set:restaurant
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('set:restaurant')
			 ->setDescription('Set current restaurant')
			 ->addArgument('term', InputArgument::REQUIRED, 'Enter restaurant name');
	}


	/**
	 * Execute set:restaurant
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$results = $this->project->getRestaurants(
			$input->getArgument('term')
		);

		$this->prependSelector()->outputRestaurantsTable($output, $results);

		$answer = $this->getHelper('question')->ask($input, $output,
			new Question('<info>Select by index: </info><comment>#</comment>', '0')
		);

		$this->project->setRestaurant($results[$answer])->grunt(
			$this->project->getTemplate($results[$answer]['webTemplateId'])
		);
	}
}