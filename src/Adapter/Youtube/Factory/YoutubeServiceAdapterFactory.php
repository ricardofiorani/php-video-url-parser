<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Youtube\Factory;

use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class YoutubeServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    public function __invoke(string $url, string $pattern, EmbedRendererInterface $renderer): VideoAdapterInterface
    {
        return new YoutubeServiceAdapter($url, $pattern, $renderer);
    }
}
