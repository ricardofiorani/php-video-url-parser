<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter;

use RicardoFiorani\VideoUrlParser\Adapter\VideoAdapterInterface;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

interface CallableServiceAdapterFactoryInterface
{
    /**
     * @param string                 $url
     * @param string                 $pattern
     * @param EmbedRendererInterface $renderer
     *
     * @return VideoAdapterInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer);
}
