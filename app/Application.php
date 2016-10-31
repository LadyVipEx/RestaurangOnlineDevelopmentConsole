<?php

namespace App;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application {

	/**
	 * Easy command manangement
	 * 
	 * @var array
	 */
	protected $commands = [
		'All' => [
			'Restaurants',
			'Templates'
		],
		'Get' => [
			'Restaurants',
			'Templates', // --with-files
			'Current'
		],
		'Set' => [
			'Restaurant',
			'Template',
			'Current'
		],
		'Duplicate' => [
			'Template'
		]
	];

	/**
	 * @var ConsoleApplication
	 */
	protected $consoleApplication;

	/**
	 * Setup symfonys consoles application
	 * 
	 * @return ConsoleApplication
	 */
	public function setupConsoleApplication()
	{
		$this->consoleApplication = new ConsoleApplication();
		$this->consoleApplication->setCatchExceptions(false);

		$this->setupConsoleCommands();

		return $this->consoleApplication;
	}

	/**
	 * Instantiate and adds the console commands to 
	 * the console application
	 * 
	 * @return $this
	 */
	protected function setupConsoleCommands()
	{
		foreach ($this->commandClasses() as $class) 
		{
			$this->consoleApplication->add(new $class);
		}

		return $this;
	}

	/**
	 * Adding console commands is such a ugly task.
	 * 
	 * @return array
	 */
    protected function commandClasses()
    {
        $consoleCommands = [];

        foreach ($this->commands as $globalCommand => $commands)
        {
            foreach ($commands as $command)
            {
                array_push($consoleCommands, 'App\Commands\\' . $globalCommand . 'Commands\\' . $globalCommand . $command);
            }
        }

        return $consoleCommands;
    }

	/**
	 * Run the application
	 * 
	 * @return void
	 */
	public function run()
	{
		$this->setupConsoleApplication()->run(); 
	}
}