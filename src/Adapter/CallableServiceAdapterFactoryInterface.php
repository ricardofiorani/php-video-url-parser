<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:57.
 */
namespace RicardoFiorani\Adapter;

use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Renderer\EmbedRendererInterface;

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
