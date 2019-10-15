<?php declare(strict_types=1);

namespace RicardoFiorani\Matcher;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Exception\ServiceNotAvailableException;

class VideoServiceMatcher
{
    private ServicesContainer $serviceContainer;

    private array $parsedUrls = [];

    public function __construct()
    {
        $this->serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
    }

    /**
     * @throws ServiceNotAvailableException
     */
    public function parse(string $url): VideoAdapterInterface
    {
        if (isset($this->parsedUrls[$url])) {
            return $this->parsedUrls[$url];
        }

        /** @var array $patterns */
        /** @var string $serviceName */
        foreach ($this->getServiceContainer()->getPatterns() as $serviceName => $patterns) {
            /** @var string $pattern */
            foreach ($patterns as $pattern) {
                if (1 === preg_match($pattern, $url)) {
                    $factory = $this->getServiceContainer()->getFactory($serviceName);

                    return $this->parsedUrls[$url] = $factory(
                        $url,
                        $pattern,
                        $this->getServiceContainer()->getRenderer()
                    );
                }
            }
        }

        throw new ServiceNotAvailableException(sprintf(
            'The url "%s" could not be parsed by any of the services available.',
            $url
        ));
    }

    public function getServiceContainer(): ServicesContainer
    {
        return $this->serviceContainer;
    }

    public function setServiceContainer(ServicesContainer $serviceContainer): void
    {
        $this->serviceContainer = $serviceContainer;
    }
}
