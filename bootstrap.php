<?php

/**
 * Composers autoloader
 */
require 'vendor/autoload.php';

error_reporting(-1);

(new \Dotenv\Dotenv(__DIR__))->load();

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