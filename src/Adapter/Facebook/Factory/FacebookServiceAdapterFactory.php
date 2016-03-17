<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 02/09/2015
 * Time: 22:41.
 */
namespace RicardoFiorani\Adapter\Facebook\Factory;

use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

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
