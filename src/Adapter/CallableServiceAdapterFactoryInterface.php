<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter;

use RicardoFiorani\Renderer\EmbedRendererInterface;

interface CallableServiceAdapterFactoryInterface
{
    public function __invoke(string $url, string $pattern, EmbedRendererInterface $renderer): VideoAdapterInterface;
}
