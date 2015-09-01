<?php

namespace RicardoFiorani\Detector;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Exception\ServiceNotAvailableException;

/**
 * @author Ricardo Fiorani
 */
class VideoServiceDetector
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
     * VideoServiceDetector constructor.
     */
    public function __construct()
    {
        $this->serviceContainer = ServicesContainerFactory::createNewServiceDetector();
    }

    /**
     * @param string $url
     * @return VideoAdapterInterface
     * @throws ServiceNotAvailableException
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
        throw new ServiceNotAvailableException();
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
    public function setServiceContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }


}
