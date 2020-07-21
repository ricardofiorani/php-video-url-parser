<?php declare(strict_types = 1);
namespace RicardoFiorani\Tests\VideoUrlParser\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;


class DailymotionServiceAdapterTest extends TestCase
{

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testServiceNameIsString($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertIsString( $dailymotionVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertIsBool( $dailymotionVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertIsArray($dailymotionVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetThumbnailIsString($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertIsString(
            $dailymotionVideo->getThumbnail(DailymotionServiceAdapter::THUMBNAIL_DEFAULT));
        $this->assertIsString( $dailymotionVideo->getSmallThumbnail());
        $this->assertIsString( $dailymotionVideo->getMediumThumbnail());
        $this->assertIsString( $dailymotionVideo->getLargeThumbnail());
        $this->assertIsString( $dailymotionVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $dailymotionVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetEmbedUrl($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertIsString( $dailymotionVideo->getEmbedUrl());
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
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertTrue($dailymotionVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return [
            [
                'http://www.dailymotion.com/video/x332a71_que-categoria-jogador-lucas-lima-faz-golaco-em-treino-do-santos_sport'
            ],
        ];
    }

    /**
     * @param $url
     * @return DailymotionServiceAdapter
     * @throws ServiceNotAvailableException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $dailymotionVideo = $videoParser->parse($url);

        return $dailymotionVideo;
    }

}
