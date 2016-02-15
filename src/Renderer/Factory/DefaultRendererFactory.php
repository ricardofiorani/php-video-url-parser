<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 01:05.
 */
namespace RicardoFiorani\Renderer\Factory;

use RicardoFiorani\Renderer\DefaultRenderer;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DefaultRendererFactory implements RendererFactoryInterface
{
    /**
     * @return EmbedRendererInterface
     */
    public function __invoke()
    {
        $renderer = new DefaultRenderer();

        return $renderer;
    }
}
