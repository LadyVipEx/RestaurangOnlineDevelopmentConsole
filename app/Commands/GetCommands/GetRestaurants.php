<?php

namespace App\Commands\GetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\Services\Project\ProjectService;

class GetRestaurants extends GetCommand {

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
	 * Set configuration for get:restaurants
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('get:restaurants')
			 ->setDescription('Lists all restaurants in database by argument')
			 ->addArgument('term', InputArgument::REQUIRED, 'Enter search term');
	}


	/**
	 * Execute get:restaurants
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$term = $input->getArgument('term');

		$this->outputRestaurantsTable($output,
			$this->project->getRestaurants($term)
		);
	}
}