<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 02/09/2015
 * Time: 23:55
 */

namespace RicardoFiorani\Test\Adapter;

use PHPUnit_Framework_TestCase;
use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;


class DailymotionServiceAdapterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testServiceNameIsString($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $dailymotionVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertInternalType('bool', $dailymotionVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertInternalType('array', $dailymotionVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetThumbnailIsString($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertInternalType('string',
            $dailymotionVideo->getThumbnail(DailymotionServiceAdapter::THUMBNAIL_DEFAULT));
        $this->assertInternalType('string', $dailymotionVideo->getSmallThumbnail());
        $this->assertInternalType('string', $dailymotionVideo->getMediumThumbnail());
        $this->assertInternalType('string', $dailymotionVideo->getLargeThumbnail());
        $this->assertInternalType('string', $dailymotionVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->setExpectedException('\\RicardoFiorani\\Adapter\\Exception\\InvalidThumbnailSizeException');
        $dailymotionVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetEmbedUrl($url)
    {
        $dailymotionVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $dailymotionVideo->getEmbedUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetEmbedUrlUsesRightScheme($url)
    {
        $videoObject = $this->getMockingObject($url);
        $embedUrl = $videoObject->getEmbedUrl(false, true);
        $this->assertContains('https', $embedUrl);

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
        return array(
            array(
                'http://www.dailymotion.com/video/x332a71_que-categoria-jogador-lucas-lima-faz-golaco-em-treino-do-santos_sport'
            ),
        );
    }

    /**
     * @param $url
     * @return DailymotionServiceAdapter
     * @throws VideoServiceNotCompatibleException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $dailymotionVideo = $videoParser->parse($url);

        return $dailymotionVideo;
    }

}
