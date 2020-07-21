<?php declare(strict_types=1);

namespace RicardoFiorani\Tests\VideoUrlParser\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;

class YoutubeServiceAdapterTest extends TestCase
{
    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->assertIsString($youtubeVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->assertIsBool($youtubeVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->assertIsArray($youtubeVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->assertIsString($youtubeVideo->getSmallThumbnail());
        $this->assertIsString($youtubeVideo->getMediumThumbnail());
        $this->assertIsString($youtubeVideo->getLargeThumbnail());
        $this->assertIsString($youtubeVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $youtubeVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfEmbedUrlIsString($url)
    {
        $youtubeVideo = $this->getMockingObject($url);
        $this->assertIsString($youtubeVideo->getEmbedUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetEmbedUrlUsesRightScheme($url)
    {
        $videoObject = $this->getMockingObject($url);
        $embedUrl = $videoObject->getEmbedUrl(false, true);
        $this->assertStringContainsStringIgnoringCase('https', $embedUrl);

        $embedUrl = $videoObject->getEmbedUrl(false, false);
        $this->assertEquals(parse_url($url, PHP_URL_SCHEME), parse_url($embedUrl, PHP_URL_SCHEME));
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfIsEmbeddable($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertTrue($facebookVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return [
            [
                'https://www.youtube.com/watch?v=uarCDXc3BjU',
                'https://youtu.be/uarCDXc3BjU',
                'http://youtu.be/uarBDXc3BjU',
                '<iframe width="560" height="315" src="https://www.youtube.com/embed/uarCDXc3BjU" allowfullscreen></iframe>',
            ],
        ];
    }

    /**
     * @param $url
     * @return YoutubeServiceAdapter
     * @throws ServiceNotAvailableException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $youtubeVideo = $videoParser->parse($url);

        return $youtubeVideo;
    }
}
