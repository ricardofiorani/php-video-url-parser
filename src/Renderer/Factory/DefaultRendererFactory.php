<?php declare(strict_types=1);

namespace RicardoFiorani\Renderer\Factory;

use RicardoFiorani\Renderer\DefaultRenderer;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DefaultRendererFactory implements RendererFactoryInterface
{
    public function __invoke(): EmbedRendererInterface
    {
        $renderer = new DefaultRenderer();

        return $renderer;
    }
}
