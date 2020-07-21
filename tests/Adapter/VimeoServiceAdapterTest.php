<?php declare(strict_types = 1);
namespace RicardoFiorani\Tests\VideoUrlParser\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;

class VimeoServiceAdapterTest extends TestCase
{
    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsString( $vimeoVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testVideoTitleIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsString( $vimeoVideo->getTitle());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testVideoDescriptionIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsString( $vimeoVideo->getDescription());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsBool($vimeoVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsArray($vimeoVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsString( $vimeoVideo->getSmallThumbnail());
        $this->assertIsString( $vimeoVideo->getMediumThumbnail());
        $this->assertIsString( $vimeoVideo->getLargeThumbnail());
        $this->assertIsString( $vimeoVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $vimeoVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetEmbedUrl($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertIsString( $vimeoVideo->getEmbedUrl());
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
     */
    public function testIfIsEmbeddable($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertTrue($vimeoVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return [
            [
                'https://vimeo.com/8733915',
                'https://vimeo.com/channels/staffpicks/154766467',
            ],
        ];
    }

    /**
     * @param $url
     * @return VimeoServiceAdapter
     * @throws ServiceNotAvailableException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $vimeoVideo = $videoParser->parse($url);

        return $vimeoVideo;
    }
}
