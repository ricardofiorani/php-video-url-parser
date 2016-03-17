<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56.
 */
namespace RicardoFiorani\Adapter\Vimeo\Factory;

use RicardoFiorani\Adapter\CallableServiceAdapterFactoryInterface;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Renderer\EmbedRendererInterface;

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
        $adapter = new VimeoServiceAdapter($url, $pattern, $renderer);

        return $adapter;
    }
}
