<?php declare(strict_types=1);

namespace RicardoFiorani\Matcher;

use Doctrine\Common\Cache\ArrayCache;
use Psr\SimpleCache\CacheItemInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;
use Roave\DoctrineSimpleCache\SimpleCacheAdapter;

class VideoServiceMatcher
{
    private $serviceContainer;
    private $cache;

    public function __construct()
    {
        $this->serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
        $this->cache = new SimpleCacheAdapter(new ArrayCache());
    }

    public function setCache(CacheItemInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string|\Psr\Http\Message\UriInterface $url
     * @throws VideoServiceNotCompatibleException
     */
    public function parse($url): VideoAdapterInterface
    {
        $url = (string) $url;
        $cacheKey = (string) crc32($url);
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        /** @var array $patterns */
        /** @var string $serviceName */
        foreach ($this->getServiceContainer()->getPatterns() as $serviceName => $patterns) {
            /** @var string $pattern */
            foreach ($patterns as $pattern) {
                if (false != preg_match($pattern, $url)) {
                    $factory = $this->getServiceContainer()->getFactory($serviceName);
                    $renderer = $this->getServiceContainer()->getRenderer();
                    $adapter = $factory($url, $pattern, $renderer);
                    $this->cache->set($cacheKey, $adapter);

                    return $adapter;
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
