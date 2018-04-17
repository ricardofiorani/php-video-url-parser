<?php

namespace RicardoFiorani\Matcher;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;

/**
 * @author Ricardo Fiorani
 */
class VideoServiceMatcher
{
    /**
     * @var ServicesContainer
     */
    private $serviceContainer;

    /**
     * @var array
     */
    private $parsedUrls = array();

    /**
     * VideoServiceMatcher constructor.
     */
    public function __construct()
    {
        $this->serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
    }

    /**
     * @param string $url
     *
     * @return VideoAdapterInterface
     *
     * @throws VideoServiceNotCompatibleException
     */
    public function parse($url)
    {
        if (isset($this->parsedUrls[$url])) {
            return $this->parsedUrls[$url];
        }

        /** @var array $patterns */
        /** @var string $serviceName */
        foreach ($this->getServiceContainer()->getPatterns() as $serviceName => $patterns) {
            /** @var string $pattern */
            foreach ($patterns as $pattern) {
                if (false != preg_match($pattern, $url)) {
                    $factory = $this->getServiceContainer()->getFactory($serviceName);

                    return $this->parsedUrls[$url] = $factory($url, $pattern,
                        $this->getServiceContainer()->getRenderer());
                }
            }
        }

        throw new VideoServiceNotCompatibleException(
            sprintf('The url "%s" could not be parsed by any of the services available.', $url)
        );
    }

    /**
     * @return ServicesContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @param ServicesContainer $serviceContainer
     */
    public function setServiceContainer(ServicesContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }
}
