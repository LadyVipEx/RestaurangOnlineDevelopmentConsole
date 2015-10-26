<?php

namespace App\Commands\TemplateCommands;

use Symfony\Component\Console\Output\OutputInterface;
use App\Commands\AbstractCommand;


class TemplateCommand extends AbstractCommand {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Output's a table with standard headers
	 * 
	 * @param  OutputInterface $output 
	 * @param  array           $rows   
	 * @return Terminal output
	 */
	protected function outputTemplateTable(OutputInterface $output, array $rows)
	{
		$this->outputTable($output, [
			'Name', 
			'Template', 
			'Base template', 
			'Global template', 
			'Icon folder'
		], $rows);
	}
}