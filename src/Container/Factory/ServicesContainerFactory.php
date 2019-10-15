<?php declare(strict_types=1);

namespace RicardoFiorani\Container\Factory;

use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Exception\DuplicatedServiceNameException;

class ServicesContainerFactory
{
    private array $config;

    public function __construct(array $config = [])
    {
        if (empty($config)) {
            $config = require __DIR__ . '/../../../config/config.php';
        }

        $this->config = $config;
    }

    /**
     * @return ServicesContainer
     * @throws DuplicatedServiceNameException
     */
    public function __invoke(): ServicesContainer
    {
        return new ServicesContainer($this->config);
    }

    /**
     * @return ServicesContainer
     * @throws DuplicatedServiceNameException
     */
    public static function createNewServiceMatcher(): ServicesContainer
    {
        $factory = new self();

        return $factory();
    }
}
