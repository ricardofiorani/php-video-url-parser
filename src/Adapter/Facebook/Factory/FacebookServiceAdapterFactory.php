<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter\Facebook\Factory;

use RicardoFiorani\VideoUrlParser\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\VideoUrlParser\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\VideoUrlParser\Adapter\VideoAdapterInterface;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

class FacebookServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    /**
     * @param string                 $url
     * @param string                 $pattern
     * @param EmbedRendererInterface $renderer
     *
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        return new FacebookServiceAdapter($url, $pattern, $renderer);
    }
}
