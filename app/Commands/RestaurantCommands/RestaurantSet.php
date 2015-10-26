<?php 

namespace App\Commands\RestaurantCommands;

use App\Commands\RestaurantCommands\RestaurantCommand;
use Symfony\Component\Console\Output\OutputInterface;
use App\Commands\WorkspaceCommands\WorkspaceCurrent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use App\Services\Project\ProjectService;

class RestaurantSet extends RestaurantCommand {

	/**
	 * @var App\Commands\WorkspaceCommands\WorkspaceCurrent
	 */
	protected $workspaceCurrent;

	/**
	 * @var App\Services\Project\ProjectService
	 */
	protected $projectService;

	public function __construct()
	{
		parent::__construct();

		$this->workspaceCurrent = new WorkspaceCurrent;

		$this->projectService = new ProjectService;
	}

	/**
	 * Set configuration for the command
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('restaurant:set')
			 ->setDescription('Set restaurant')
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
		// Search for restaurants with like input
		$results = $this->projectService->searchRestaurants(
			$input->getArgument('name')
		);

		// Output all results for display with index selectionn
		$this->outputRestaurantsTableWithSelection($output, $results);

		// Ask question which template should be choosen
		$index = $this->getHelper('question')
					   ->ask($input, $output, 
					   		new Question('<info>Select by index: </info><comment>#</comment>', '0')
					   );

		// Set the resturant with service by selected index
		$this->projectService->setRestaurant($results[$index]);

		// Execute output command for current workspace
		$this->workspaceCurrent->execute($input, $output);
	}
}
