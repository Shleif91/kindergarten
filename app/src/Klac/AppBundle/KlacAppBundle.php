<?php

namespace Klac\AppBundle;

use Klac\AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KlacAppBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new AppExtension();
    }
}
