<?php

namespace App\Commands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;

class AbstractCommand extends Command {

	protected $selector = false;

	/**
	 * Using the construct Command
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function prependSelector()
	{
		$this->selector = true;

		return $this;
	}

	/**
	 * Quick way off outputting a table to the terminal
	 *
	 * @param  OutputInterface $output
	 * @param  array           $header
	 * @param  array           $rows
	 */
	protected function outputTable(OutputInterface $output, array $header, array $rows)
	{
		$table = new Table($output);

		if ($this->selector == true)
		{
			array_unshift($header, '#');

            for ($i = 0; $i < count($rows); $i++)
            {
                $information = $rows[$i];

				$controlledRows[] = array_merge(['<comment>' . $i . '</comment>'], $information);
            }

			$rows = isset($controlledRows) ? $controlledRows : [];
		}

		$table->setHeaders($header)
			  ->setRows($rows)
			  ->render();
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
		return $this->outputTable($output, [
			'Name',
			'Template',
			'Base template',
			'Global template',
			'Icon folder'
		], $rows);
	}

	/**
	 * Output's a table with standard headers
	 *
	 * @param  OutputInterface $output
	 * @param  array           $rows
	 */
	protected function outputRestaurantsTable(OutputInterface $output, array $rows)
	{
		foreach ($rows as $index => $information)
		{
			$controlledRows[] = [
				$information['restaurantName'],
				$information['userName'],
				$information['webTemplateId'],
				$information['clientKey'],
			];
		}

		$this->outputTable($output, [
			'Restaurant name',
			'Username',
			'Template',
			'Clientkey'
		], (isset($controlledRows) ? $controlledRows : []));
	}

	/**
	 * Output's a table with standard headers
	 *
	 * @param  OutputInterface $output
	 * @param  array           $rows
	 * @return Terminal output
	 */
	protected function outputCurrentTable(OutputInterface $output, array $rows)
	{
		$controlledRows = [[
			$rows['restaurantName'],
			$rows['userName'],
			$rows['webTemplateId'],
			$rows['clientKey'],
			$rows['online'] 	  ? 'true' : 'false',
			$rows['featurePopup'] ? 'true' : 'false',
			$rows['assets'],
			$rows['assetsBS3']
		]];

		return $this->outputTable($output, [
			'Restaurant name',
			'Username',
			'Template',
			'Clientkey',
			'Online',
			'Feature Popup',
			'Grunt # bs2',
			'Grunt # bs3'
		], $controlledRows);
	}
}