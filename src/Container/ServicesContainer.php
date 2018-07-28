<?php declare(strict_types=1);

namespace RicardoFiorani\Container;

use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Container\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class ServicesContainer
{
    private $services = array();
    private $patterns = array();
    private $factories = array();
    private $instantiatedFactories = array();
    private $renderer;
    private $rendererName;

    /**
     * @throws DuplicatedServiceNameException
     */
    public function __construct(array $config = [])
    {
        if (false == empty($config)) {
            $this->registerFromConfig($config);
        }
    }

    /**
     * @throws DuplicatedServiceNameException
     */
    private function registerFromConfig(array $config)
    {
        foreach ($config['services'] as $serviceName => $serviceConfig) {
            $this->registerService($serviceName, $serviceConfig['patterns'], new $serviceConfig['factory']);
        }

        $this->setRenderer($config['renderer']['name'], new $config['renderer']['factory']);
    }

    /**
     * @throws DuplicatedServiceNameException
     */
    public function registerService(string $serviceName, array $regex, callable $factory)
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

    public function setRenderer(string $rendererName, callable $rendererFactory)
    {
        $this->rendererName = $rendererName;
        $this->renderer = $rendererFactory();
    }

    public function getRenderer(): EmbedRendererInterface
    {
        return $this->renderer;
    }

    public function getServiceNameList(): array
    {
        return $this->services;
    }

    public function hasService($serviceName): bool
    {
        return in_array($serviceName, $this->services);
    }

    public function getServices(): array
    {
        return $this->services;
    }

    public function getPatterns(): array
    {
        return $this->patterns;
    }

    public function getFactory($serviceName): CallableServiceAdapterFactoryInterface
    {
        $factory = new $this->factories[$serviceName]();
        if (isset($this->instantiatedFactories[$serviceName])) {
            return $this->instantiatedFactories[$serviceName];
        }

        return $this->instantiatedFactories[$serviceName] = $factory;
    }
}
