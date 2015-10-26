<?php 

namespace App\Commands\WorkspaceCommands;

use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor as TemplatesConfiguration;
use App\Services\Project\ProjectService as Workspace;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;

class WorkspaceCurrent extends WorkspaceCommand {

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
		$this->setName('workspace:current')
			 ->setDescription('Display current workspace');
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
		$this->outputWorkspaceTable($output, 
			$this->workspace->current()
		);
	}
}
