<?php

namespace App\Commands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;

class AbstractCommand extends Command {	
	
	/**
	 * Using the construct Command
	 */
	public function __construct() 
	{
		parent::__construct();
	}

	/**
	 * Quick way off outputting a table to the terminal
	 * 
	 * @param  OutputInterface $output
	 * @param  array           $header
	 * @param  array           $rows  
	 * @return Terminal void             
	 */
	protected function outputTable(OutputInterface $output, array $header, array $rows)
	{
		$table = new Table($output);

		$table->setHeaders($header)
			  ->setRows($rows)
			  ->render();
	}
}