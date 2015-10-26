<?php 

namespace App\Commands\WorkspaceCommands;

use Symfony\Component\Console\Output\OutputInterface;
use App\Commands\WorkspaceCommands\WorkspaceCurrent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\Project\ProjectService;

class WorkspaceSet extends WorkspaceCommand {

	/**
	 * @var App\Commands\WorkspaceCommands\WorkspaceCurrent
	 */
	protected $currentCommand;

	/**
	 * @var App\Services\Project\ProjectService
	 */
	protected $project;

	public function __construct()
	{
		parent::__construct();

		$this->project = new ProjectService;

		$this->currentCommand = new WorkspaceCurrent;
	}

	/**
	 * Set configuration for the command
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('workspace:set')
			 ->setDescription('Set workspace')
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
		$template = $this->project->search(
			$input->getArgument('name')
		);

		$this->project->setTemplate(
			reset($template)
		);	

		$this->currentCommand->execute($input, $output);
	}
}
