<?php declare(strict_types=1);

namespace RicardoFiorani\Tests\VideoUrlParser\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;
use RicardoFiorani\VideoUrlParser\Exception\ThumbnailSizeNotAvailable;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;

class FacebookServiceAdapterTest extends TestCase
{
    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testServiceNameIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsString($facebookVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsBool($facebookVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsArray($facebookVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetThumbnailIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsString(
            $facebookVideo->getThumbnail(FacebookServiceAdapter::THUMBNAIL_SIZE_DEFAULT));
        $this->assertIsString($facebookVideo->getMediumThumbnail());
        $this->assertIsString($facebookVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $facebookVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetSmallThumbnailThrowsException($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->expectException(ThumbnailSizeNotAvailable::class);
        $facebookVideo->getSmallThumbnail();
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetLargeThumbnailThrowsException($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->expectException(ThumbnailSizeNotAvailable::class);
        $facebookVideo->getLargeThumbnail();
    }


    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfEmbedUrlIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertIsString($facebookVideo->getEmbedUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
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
