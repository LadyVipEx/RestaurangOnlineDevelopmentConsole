<?php

namespace App\Commands\RestaurantCommands;

use Symfony\Component\Console\Output\OutputInterface;
use App\Commands\AbstractCommand;

class RestaurantCommand extends AbstractCommand {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Output's a table with standard headers
	 * 
	 * @param  OutputInterface $output 
	 * @param  array           $rows   
	 * @return Terminal void
	 */
	protected function outputRestaurantsTableWithSelection(OutputInterface $output, array $rows)
	{
		foreach ($rows as $index => $information) 
		{
			$controlledRows[] = [
				'<comment>' . $index . '</comment>',
				$information['restaurantName'],
				$information['userName'],
				$information['webTemplateId'],
				$information['clientKey'],
			];
		}

		$this->outputTable($output, [
			'#',
			'Restaurant name', 
			'Username', 
			'Template', 
			'Clientkey'
		], $controlledRows);
	}

}