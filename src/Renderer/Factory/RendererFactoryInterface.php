<?php declare(strict_types = 1);

namespace RicardoFiorani\VideoUrlParser\Renderer\Factory;

use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

interface RendererFactoryInterface
{
    /**
     * @return EmbedRendererInterface
     */
    public function __invoke();
}
