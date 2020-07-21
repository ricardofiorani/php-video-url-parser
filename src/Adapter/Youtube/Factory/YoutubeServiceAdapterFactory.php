<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter\Youtube\Factory;

use RicardoFiorani\VideoUrlParser\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\VideoUrlParser\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

class YoutubeServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    /**
     * @param string $url
     * @param string $pattern
     *
     * @return YoutubeServiceAdapter
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        $adapter = new YoutubeServiceAdapter($url, $pattern, $renderer);

        return $adapter;
    }
}
