<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 10/02/2016
 * Time: 18:45
 */

namespace RicardoFiorani\Test\Renderer;


use PHPUnit_Framework_TestCase;
use RicardoFiorani\Renderer\DefaultRenderer;

class DefaultRendererTest extends PHPUnit_Framework_TestCase
{

    private $embedUrl = 'http://test.unit';
    private $width = '1920';
    private $height = '1080';

    public function testIfRenderReturnsString()
    {
        $renderer = new DefaultRenderer();

        $output = $renderer->renderVideoEmbedCode($this->embedUrl, $this->width, $this->height);
        $this->assertInternalType('string', $output);
        $this->assertContains($this->embedUrl, $output);
        $this->assertContains($this->width, $output);
        $this->assertContains($this->height, $output);
    }
}
