<?php

namespace Klac\CoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class IsolatedTestDatabase
{
	protected static $application;

	public static function initialize()
	{
		self::getApplication()->run(new ArrayInput(array(
			'command' => 'doctrine:database:drop',
			'--force' => true,
		)), new ConsoleOutput());

		self::getApplication()->run(new ArrayInput(array(
			'command' => 'doctrine:database:create'
		)), new ConsoleOutput());

		self::getApplication()->run(new ArrayInput(array(
			'command'          => 'doctrine:schema:update',
			'--force'          => true,
			'--no-interaction' => true,
		)), new ConsoleOutput());

		self::getApplication()->run(new ArrayInput(array(
			'command'          => 'doctrine:fixtures:load',
			'--no-interaction' => true,
		)), new ConsoleOutput());
	}
	
	protected static function getApplication()
	{
		if (null === self::$application) {
			$kernel = new \AppKernel('test', false);
			$kernel->boot();
			self::$application = new Application($kernel);
			self::$application->setAutoExit(false);
		}

		return self::$application;
	}
}
