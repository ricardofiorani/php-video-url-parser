<?php declare(strict_types=1);

namespace RicardoFiorani\Renderer;

interface EmbedRendererInterface
{
    public function renderVideoEmbedCode(string $embedUrl, int $width, int $height): string;
}
