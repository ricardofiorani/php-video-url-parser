<?php declare(strict_types = 1);

namespace RicardoFiorani\VideoUrlParser\Renderer\Factory;

use RicardoFiorani\VideoUrlParser\Renderer\DefaultRenderer;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

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
