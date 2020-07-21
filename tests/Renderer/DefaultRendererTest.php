<?php declare(strict_types=1);

namespace RicardoFiorani\Tests\VideoUrlParser\Renderer;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Renderer\DefaultRenderer;

class DefaultRendererTest extends TestCase
{

    private $embedUrl = 'http://tests.unit';
    private $width = '1920';
    private $height = '1080';

    public function testIfRenderReturnsString()
    {
        $renderer = new DefaultRenderer();

        $output = $renderer->renderVideoEmbedCode($this->embedUrl, $this->width, $this->height);
        $this->assertIsString($output);
        $this->assertStringContainsStringIgnoringCase($this->embedUrl, $output);
        $this->assertStringContainsStringIgnoringCase($this->width, $output);
        $this->assertStringContainsStringIgnoringCase($this->height, $output);
    }
}
