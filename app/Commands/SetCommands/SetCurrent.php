<?php

namespace App\Commands\SetCommands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

use App\Services\Project\ProjectService;

class SetCurrent extends SetCommand {

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
	 * Set configuration for set:current
	 * 
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('set:current')
			 ->setDescription('Set current options')
			 ->addOption('toggle-popup', null, InputOption::VALUE_NONE, 
			 	'Will toggle the popup for current Restaurant'
			 )
			 ->addOption('online', null, InputOption::VALUE_NONE, 
			 	'Will set current Restaurant online'
			 )
			 ->addOption('offline', null, InputOption::VALUE_NONE, 
			 	'Will set current Restaurant offline'
			 );
	}


	/**
	 * Execute set:current
	 * 
	 * @param  InputInterface  $input
	 * @param  OutputInterface $outpu
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if ($input->getOption('online'))
			$this->project->setCurrentOnline();

		if ($input->getOption('offline'))
			$this->project->setCurrentOffline();

		if ($input->getOption('toggle-popup'))
			$this->project->toggleCurrentPopup();

		$this->outputCurrentTable($output,
			$this->project->current()
		);
	}
}