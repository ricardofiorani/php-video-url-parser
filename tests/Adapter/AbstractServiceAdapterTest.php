<?php declare(strict_types = 1);
namespace RicardoFiorani\Tests\VideoUrlParser\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;
use RicardoFiorani\VideoUrlParser\Renderer\DefaultRenderer;

class AbstractServiceAdapterTest extends TestCase
{

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetRawUrl($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsString($facebookVideo->getRawUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testSetRawUrl($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $testUrl = 'http://tests.unit';
        $facebookVideo->setRawUrl($testUrl);
        $this->assertEquals($testUrl, $facebookVideo->getRawUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetPattern($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsString( $facebookVideo->getPattern());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testSetPattern($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $pattern = '##tests.unit##';
        $facebookVideo->setPattern($pattern);
        $this->assertEquals($pattern, $facebookVideo->getPattern());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetAndSetRenderer($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $renderer = new DefaultRenderer();
        $facebookVideo->setRenderer($renderer);
        $this->assertEquals($renderer, $facebookVideo->getRenderer());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetEmbedCode($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $embedCode = $facebookVideo->getEmbedCode(1920, 1080);
        $this->assertIsString( $embedCode);
        $this->assertStringContainsString('1920', $embedCode);
        $this->assertStringContainsString('1080', $embedCode);
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param $url
     */
    public function testGetScheme($url)
    {
        $facebookVideo = $this->getMockingObject($url);

        $originalScheme = $facebookVideo->getScheme(false);
        $this->assertEquals(parse_url($url, PHP_URL_SCHEME), $originalScheme);

        $schemeSecure = $facebookVideo->getScheme(true);
        $this->assertEquals('https', $schemeSecure);
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return [
            [
                'https://www.facebook.com/zuck/videos/10102367711349271'
            ],
        ];
    }

    /**
     * @param $url
     * @return FacebookServiceAdapter
     * @throws ServiceNotAvailableException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $facebookVideo = $videoParser->parse($url);

        return $facebookVideo;
    }
}
