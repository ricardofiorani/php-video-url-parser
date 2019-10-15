<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Facebook\Factory;

use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class FacebookServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    public function __invoke(string $url, string $pattern, EmbedRendererInterface $renderer): VideoAdapterInterface
    {
        return new FacebookServiceAdapter($url, $pattern, $renderer);
    }
}
