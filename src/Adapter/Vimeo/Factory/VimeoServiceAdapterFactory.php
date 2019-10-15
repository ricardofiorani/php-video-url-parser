<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Vimeo\Factory;

use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class VimeoServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    public function __invoke(string $url, string $pattern, EmbedRendererInterface $renderer): VideoAdapterInterface
    {
        return new VimeoServiceAdapter($url, $pattern, $renderer);
    }
}
