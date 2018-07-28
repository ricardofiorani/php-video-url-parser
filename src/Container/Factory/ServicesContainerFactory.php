<?php declare(strict_types=1);

namespace RicardoFiorani\Container\Factory;

use RicardoFiorani\Container\ServicesContainer;

class ServicesContainerFactory
{
    public function __invoke(): ServicesContainer
    {
        $configFile = require __DIR__.'/../../../config/config.php';
        $servicesContainer = new ServicesContainer($configFile);

        return $servicesContainer;
    }

    public static function createNewServiceMatcher(): ServicesContainerFactory
    {
        $factory = new self();

        return $factory();
    }
}
