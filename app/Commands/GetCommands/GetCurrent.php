<?php

namespace App\Commands\GetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

use App\Services\Project\ProjectService;

class GetCurrent extends GetCommand {

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
	 * Set configuration for get:current
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('get:current')
			 ->setDescription('List current user in database');
	}


	/**
	 * Execute get:current
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->outputCurrentTable($output,
			$this->project->current()
		);		
	}
}