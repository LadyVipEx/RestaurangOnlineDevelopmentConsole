<?php

/**
 * Composers autoloader
 */
require join(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'autoload.php']);

error_reporting(-1);

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required([
    'database.hostname',
    'database.username',
    'database.password',
    'database.database'
]);

set_error_handler(function($severity, $message, $file, $line)
{
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function(\Exception $exception)
{
    $output = new Symfony\Component\Console\Output\ConsoleOutput();

    (new Symfony\Component\Console\Application())->renderException($exception, $output);
    $output->writeln('<info>' . $exception->getFile() . ':' . $exception->getLine() . '</info>');
    $output->writeln('');
});

$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection([
    'driver' 		=> 'mysql',
    'host'	 		=> getenv('database.hostname'),
    'database' 		=> getenv('database.database'),
    'username' 		=> getenv('database.username'),
    'password' 		=> getenv('database.password'),
    'charset'  		=> 'utf8',
    'collation' 	=> 'utf8_unicode_ci',
    'prefix' 		=> ''
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
