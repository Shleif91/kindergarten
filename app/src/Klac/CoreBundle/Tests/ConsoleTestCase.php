<?php

namespace Klac\CoreBundle\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConsoleTestCase extends KernelTestCase
{
    /** @var EntityManager */
    protected $em;

    /** @var ContainerInterface */
    protected $container;

    /** @var Application */
    protected $application;

    protected function setUp()
    {
        parent::setUp();

        self::bootKernel();

        $this->application = new Application(self::$kernel);
        $this->container = self::$kernel->getContainer();
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
    }
}