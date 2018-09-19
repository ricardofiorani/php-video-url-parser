<?php

namespace RicardoFiorani\Container;

use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Container\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class ServicesContainer
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
     * ServicesContainer constructor.
     *
     * @param array $config
     * @throws DuplicatedServiceNameException
     */
    public function __construct(array $config = array())
    {
        if (false == empty($config)) {
            $this->registerFromConfig($config);
        }
    }

    /**
     * Loads de default config file.
     *
     * @param array $config
     *
     * @throws DuplicatedServiceNameException
     */
    private function registerFromConfig(array $config)
    {
        foreach ($config['services'] as $serviceName => $serviceConfig) {
            $this->registerService($serviceName, $serviceConfig['patterns'], $serviceConfig['factory']);
        }

        $this->setRenderer($config['renderer']['name'], $config['renderer']['factory']);
    }

    /**
     * Register a Service.
     *
     * @param string $serviceName
     * @param array $regex
     * @param string|callable $factory
     *
     * @throws DuplicatedServiceNameException
     */
    public function registerService($serviceName, array $regex, $factory)
    {
        if ($this->hasService($serviceName)) {
            throw new DuplicatedServiceNameException(
                sprintf(
                    'The service "%s" is already registered in the container.',
                    $serviceName
                )
            );
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
     *
     * @return bool
     */
    public function hasService($serviceName)
    {
        return in_array($serviceName, $this->services);
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return array
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @param string $serviceName
     *
     * @return CallableServiceAdapterFactoryInterface
     */
    public function getFactory($serviceName)
    {
        $factory = new $this->factories[$serviceName]();
        if (isset($this->instantiatedFactories[$serviceName])) {
            return $this->instantiatedFactories[$serviceName];
        }

        return $this->instantiatedFactories[$serviceName] = $factory;
    }
}
