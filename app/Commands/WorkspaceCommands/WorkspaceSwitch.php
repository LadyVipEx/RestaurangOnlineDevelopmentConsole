<?php 

namespace App\Commands\WorkspaceCommands;

use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor as TemplatesConfiguration;
use App\Services\Project\ProjectService as Workspace;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

class WorkspaceSet extends WorkspaceCommand {

	/**
	 * @var App\Services\Project\ProjectService
	 */
	protected $workspace;

	public function __construct()
	{
		parent::__construct();

		$this->workspace = new Workspace;
	}

	/**
	 * Set configuration for the command
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('workspace:switch')
			 ->setDescription('Switch workspace')
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
		$by = strlen($input->getArgument('name')) === 18 ? 'clientkey' : 'restaurantName' ;

		$restaurant = $this->workspace->searchRestaurant(
			$input->getArgument('name'), $by
		);

		$this->workspace->setRestaurant(
			reset($restaurant)
		);	
	}
}
