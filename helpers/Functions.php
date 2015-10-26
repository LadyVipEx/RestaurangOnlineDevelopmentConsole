<?php

/**
 * Get enviroment values from all over the project
 * 
 * @param  string $enviromentValue
 * @return string
 */
function env($enviromentValue)
{
	$enviroment = new App\Config\Enviroment();

	return $enviroment->get($enviromentValue);
}

/**
 * Adding console commands is such a common task.
 * 
 * @param  array $commandClasses
 * @return array
 */
function consoleArrayTranslate($commandClasses)
{
	$consoleCommands = [];

	foreach ($commandClasses as $globalCommand => $commands) 
	{
		foreach ($commands as $command) 
		{
			array_push($consoleCommands, 'App\Commands\\' . $globalCommand . 'Commands\\' . $globalCommand . $command);
		}
	}

	return $consoleCommands;
}