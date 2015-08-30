<?php

namespace RicardoFiorani\Detector;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Exception\ServiceNotAvailableException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

/**
 * @author Ricardo Fiorani
 */
class VideoServiceDetector
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
     * @var array
     */
    private $factories = array();

    /**
     * @var array
     */
    private $instantiatedFactories = array();

    /**
     * @var EmbedRendererInterface
     */
    private $renderer;

    /**
     * @var string
     */
    private $rendererName;

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
        $configFile = require __DIR__ . '/../../config/config.php';
        foreach ($configFile['services'] as $serviceName => $serviceConfig) {
            $this->registerService($serviceName, $serviceConfig['patterns'], $serviceConfig['factory']);
        }
        $this->setRenderer($configFile['renderer']['name'], $configFile['renderer']['factory']);
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
     * @param string $rendererName
     * @param string $rendererFactory
     */
    public function setRenderer($rendererName, $rendererFactory)
    {
        $this->rendererName = $rendererName;
        $factory = new $rendererFactory();
        $this->renderer = $factory();
    }

    /**
     * @return EmbedRendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
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
                if (false != preg_match($pattern, $url)) {
                    $factory = new $this->factories[$serviceName]();
                    if (isset($this->instantiatedFactories[$serviceName])) {
                        return $this->instantiatedFactories[$serviceName]($url, $pattern, $this->renderer);
                    }

                    return $this->instantiatedFactories[$serviceName] = $factory($url, $pattern, $this->renderer);
                }
            }
        }
        throw new ServiceNotAvailableException();
    }

}
