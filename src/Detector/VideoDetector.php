<?php

namespace RicardoFiorani\Detector;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Exception\ServiceNotAvailableException;

/**
 * @author Ricardo Fiorani
 */
class VideoDetector
{
    /**
     * @var array
     */
    private $services = array();

    /**
     * @var array
     */
    private $patterns = array();

    /**
     * @var
     */
    private $factories = array();

    /**
     * VideoDetector constructor.
     */
    public function __construct()
    {
        $this->loadConfigFile();
    }

    /**
     * Loads de default config file
     * @throws DuplicatedServiceNameException
     */
    private function loadConfigFile()
    {
        $configFile = require __DIR__ . '../../config/config.php';
        foreach ($configFile as $serviceName => $serviceConfig) {
            $this->registerService($serviceName, $serviceConfig['patterns'], $serviceConfig['factory']);
        }
    }

    /**
     * Register a Service
     * @param string $serviceName
     * @param array $regex
     * @param string|callable $factory
     * @throws DuplicatedServiceNameException
     */
    public function registerService($serviceName, array $regex, $factory)
    {
        if ($this->hasService($serviceName)) {
            throw new DuplicatedServiceNameException();
        }

        $this->services[] = $serviceName;
        $this->patterns[$serviceName] = $regex;
        $this->factories[$serviceName] = $factory;
    }

    /**
     * @return array
     */
    public function getServiceNameList()
    {
        return $this->services;
    }


    /**
     * @param string $serviceName
     * @return bool
     */
    public function hasService($serviceName)
    {
        return in_array($serviceName, $this->services);
    }

    /**
     * @param string $url
     * @return VideoAdapterInterface
     * @throws ServiceNotAvailableException
     */
    public function parse($url)
    {
        /** @var array $patterns */
        /** @var string $serviceName */
        foreach ($this->patterns as $serviceName => $patterns) {
            /** @var string $pattern */
            foreach ($patterns as $pattern) {
                if (true === preg_match($pattern, $url)) {
                    return $this->factories[$serviceName]($url, $pattern);
                }
            }
        }
        throw new ServiceNotAvailableException();
    }

}
