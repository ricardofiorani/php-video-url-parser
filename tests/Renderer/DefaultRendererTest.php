<?php declare(strict_types=1);

namespace RicardoFioraniTests\Renderer;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Renderer\DefaultRenderer;

class DefaultRendererTest extends TestCase
{
    private string $embedUrl = 'http://test.unit';
    private int $width = 1920;
    private int $height = 1080;

    public function testIfRenderReturnsString(): void
    {
        $renderer = new DefaultRenderer();
        $output = $renderer->renderVideoEmbedCode($this->embedUrl, $this->width, $this->height);

        static::assertIsString($output);
        static::assertStringContainsString($this->embedUrl, $output);
        static::assertStringContainsString((string)$this->width, $output);
        static::assertStringContainsString((string)$this->height, $output);
    }
}
