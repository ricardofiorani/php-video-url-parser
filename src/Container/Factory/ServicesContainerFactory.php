<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Container\Factory;

use RicardoFiorani\VideoUrlParser\Container\ServicesContainer;

class ServicesContainerFactory
{
    public function __invoke()
    {
        $configFile = require __DIR__.'/../../../config/config.php';
        return new ServicesContainer($configFile);
    }

    public static function createNewServiceMatcher()
    {
        $factory = new self();

        return $factory();
    }
}
