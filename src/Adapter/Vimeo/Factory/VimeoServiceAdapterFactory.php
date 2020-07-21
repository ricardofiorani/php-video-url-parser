<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter\Vimeo\Factory;

use RicardoFiorani\VideoUrlParser\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\VideoUrlParser\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

class VimeoServiceAdapterFactory implements CallableServiceAdapterFactoryInterface
{
    /**
     * @param string                 $url
     * @param string                 $pattern
     * @param EmbedRendererInterface $renderer
     *
     * @return VimeoServiceAdapter
     *
     * @internal param EmbedRendererInterface $rendererInterface
     */
    public function __invoke($url, $pattern, EmbedRendererInterface $renderer)
    {
        return new VimeoServiceAdapter($url, $pattern, $renderer);
    }
}
