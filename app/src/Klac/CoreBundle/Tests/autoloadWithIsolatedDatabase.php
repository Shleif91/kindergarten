<?php

namespace Klac\CoreBundle\Tests;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var ClassLoader $loader */
$loader = require __DIR__ . '/../../../../vendor/autoload.php';
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

IsolatedTestDatabase::initialize();

return $loader;
