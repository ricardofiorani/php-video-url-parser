<?php declare(strict_types=1);

namespace RicardoFiorani\Container;

use LogicException;
use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Exception\DuplicatedServiceNameException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class ServicesContainer
{
    private array $services = [];
    private array $patterns = [];
    private array $factories = [];
    private array $instantiatedFactories = [];
    private EmbedRendererInterface $renderer;
    private string $rendererName;

    /**
     * @throws DuplicatedServiceNameException
     */
    public function __construct(array $config = [])
    {
        if (empty($config)) {
            throw new LogicException('A config must be provided for constructing a container');
        }

        $this->registerFromConfig($config);
    }

    /**
     * @throws DuplicatedServiceNameException
     */
    private function registerFromConfig(array $config): void
    {
        foreach ($config['services'] as $serviceName => $serviceConfig) {
            $this->registerService($serviceName, $serviceConfig['patterns'], $serviceConfig['factory']);
        }

        $this->setRenderer($config['renderer']['name'], $config['renderer']['factory']);
    }

    /**
     * @throws DuplicatedServiceNameException
     */
    public function registerService(string $serviceName, array $regex, $factory): void
    {
        if ($this->hasService($serviceName)) {
            throw new DuplicatedServiceNameException("{$serviceName} is already registered.");
        }

        $this->services[] = $serviceName;
        $this->patterns[$serviceName] = $regex;
        $this->factories[$serviceName] = $factory;
    }

    public function setRenderer(string $rendererName, string $rendererFactory): void
    {
        $this->rendererName = $rendererName;
        $factory = new $rendererFactory();
        $this->renderer = $factory();
    }

    public function getRenderer(): EmbedRendererInterface
    {
        return $this->renderer;
    }

    public function getServiceNameList(): array
    {
        return $this->services;
    }

    public function hasService(string $serviceName): bool
    {
        return in_array($serviceName, $this->services, false);
    }

    public function getServices(): array
    {
        return $this->services;
    }

    public function getPatterns(): array
    {
        return $this->patterns;
    }

    public function getFactory(string $serviceName): CallableServiceAdapterFactoryInterface
    {
        $factory = $this->factories[$serviceName];

        switch (true) {
            case is_callable($factory):
            case is_object($factory):
                break;
            case class_exists($factory):
                $factory = new $factory();
                break;
            default:
                throw new LogicException(
                    'The Service Factory must be either a callable, an invokable object or a class name'
                );
        }

        if (isset($this->instantiatedFactories[$serviceName])) {
            return $this->instantiatedFactories[$serviceName];
        }

        return $this->instantiatedFactories[$serviceName] = $factory;
    }
}
