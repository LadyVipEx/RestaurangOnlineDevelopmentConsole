<?php 

namespace App\Commands\TemplateCommands;

use App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor as TemplatesConfiguration;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Commands\TemplateCommands\TemplateCommand;
use App\Models\Users;

use App\Commands\Services\Template\TemplateService;

class TemplateSet extends TemplateCommand {
	
	/**
	 * @var App\ProjectActor\TemplateConfiguration\TemplateConfigurationActor
	 */
	protected $templates;

	/**
	 * @var App\Models\Users
	 */
	protected $users;

	public function __construct()
	{
		parent::__construct();

		$this->templates = new TemplatesConfiguration;

		$this->users = new Users;
	}

	/**
	 * Set configuration for the command
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('template:set')
			 ->setDescription('Set template')
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
		$this->users->setTemplate(
			$input->getArgument('name')
		);

		$output->writeln('Template switched');
	}
}
