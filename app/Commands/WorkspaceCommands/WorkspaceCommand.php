<?php

namespace App\Commands\WorkspaceCommands;

use Symfony\Component\Console\Output\OutputInterface;
use App\Commands\AbstractCommand;

class WorkspaceCommand extends AbstractCommand {

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
	protected function outputWorkspaceTable(OutputInterface $output, array $rows)
	{
		$controlledRows = [[
			$rows['restaurantName'],
			$rows['userName'],
			$rows['webTemplateId'],
			$rows['clientKey'],
			$rows['templatesBS2'],
			$rows['templatesBS3']
		]];

		$this->outputTable($output, [
			'Restaurant name', 
			'Username', 
			'Template', 
			'Clientkey',
			'Grunt # bs2',
			'Grunt # bs3'
		], $controlledRows);
	}
}