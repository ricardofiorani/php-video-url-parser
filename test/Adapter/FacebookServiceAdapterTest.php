<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 10/02/2016
 * Time: 15:47
 */

namespace RicardoFiorani\Test\Adapter;

use PHPUnit_Framework_TestCase;
use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;

class FacebookServiceAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testServiceNameIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $facebookVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertInternalType('bool', $facebookVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertInternalType('array', $facebookVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetThumbnailIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertInternalType('string',
            $facebookVideo->getThumbnail(FacebookServiceAdapter::THUMBNAIL_SIZE_DEFAULT));

        $this->assertInternalType('string', $facebookVideo->getMediumThumbnail());

        $this->assertInternalType('string', $facebookVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->setExpectedException('\\RicardoFiorani\\Adapter\\Exception\\InvalidThumbnailSizeException');
        $facebookVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetSmallThumbnailThrowsException($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->setExpectedException('\\RicardoFiorani\\Exception\\ThumbnailSizeNotAvailable');
        $facebookVideo->getSmallThumbnail();
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetLargeThumbnailThrowsException($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->setExpectedException('\\RicardoFiorani\\Exception\\ThumbnailSizeNotAvailable');
        $facebookVideo->getLargeThumbnail();
    }


    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfEmbedUrlIsString($url)
    {
        $facebookVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $facebookVideo->getEmbedUrl());
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
        $facebookVideo = $this->getMockingObject($url);
        $this->assertTrue($facebookVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return array(
            array(
                'https://www.facebook.com/zuck/videos/10102367711349271'
            ),
        );
    }

    /**
     * @param $url
     * @return FacebookServiceAdapter
     * @throws VideoServiceNotCompatibleException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $facebookVideo = $videoParser->parse($url);

        return $facebookVideo;
    }
}
