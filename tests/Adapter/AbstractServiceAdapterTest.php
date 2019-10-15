<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Renderer\DefaultRenderer;

class AbstractServiceAdapterTest extends TestCase
{
    use VideoMatcherTrait;

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetRawUrl(string $url): void
    {
        $facebookVideo = $this->parse($url);
        self::assertEquals($url, $facebookVideo->getRawUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testSetRawUrl(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $testUrl = 'http://test.unit';
        $facebookVideo->setRawUrl($testUrl);
        self::assertEquals($testUrl, $facebookVideo->getRawUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetPattern(string $url): void
    {
        $facebookVideo = $this->parse($url);
        self::assertNotEmpty($facebookVideo->getPattern());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testSetPattern(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $pattern = '##test.unit##';
        $facebookVideo->setPattern($pattern);
        static::assertEquals($pattern, $facebookVideo->getPattern());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetAndSetRenderer(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $renderer = new DefaultRenderer();
        $facebookVideo->setRenderer($renderer);
        static::assertEquals($renderer, $facebookVideo->getRenderer());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetEmbedCode(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $embedCode = $facebookVideo->getEmbedCode(1920, 1080);
        static::assertStringContainsString('1920', $embedCode);
        static::assertStringContainsString('1080', $embedCode);
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetScheme(string $url): void
    {
        $facebookVideo = $this->parse($url);

        $originalScheme = $facebookVideo->getScheme(false);
        static::assertEquals(parse_url($url, PHP_URL_SCHEME), $originalScheme);

        $schemeSecure = $facebookVideo->getScheme(true);
        static::assertEquals('https', $schemeSecure);
    }

    public function exampleUrlDataProvider(): array
    {
        return [
            [
                'https://www.facebook.com/zuck/videos/10102367711349271'
            ],
        ];
    }
}
