<?php declare(strict_types=1);

namespace RicardoFiorani\Matcher;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;

class VideoServiceMatcher
{
    private $serviceContainer;
    private $parsedUrls = array();

    public function __construct()
    {
        $this->serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
    }

    /**
     * @throws VideoServiceNotCompatibleException
     */
    public function parse($url): VideoAdapterInterface
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

    public function getServiceContainer(): ServicesContainer
    {
        return $this->serviceContainer;
    }

    public function setServiceContainer(ServicesContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }
}
