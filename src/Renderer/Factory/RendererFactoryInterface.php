<?php declare(strict_types=1);

namespace RicardoFiorani\Renderer\Factory;

use RicardoFiorani\Renderer\EmbedRendererInterface;

interface RendererFactoryInterface
{
    public function __invoke(): EmbedRendererInterface;
}
