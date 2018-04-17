<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 10/02/2016
 * Time: 17:27
 */

namespace RicardoFiorani\Test\Adapter;


use PHPUnit_Framework_TestCase;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;

class VimeoServiceAdapterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testServiceNameIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $vimeoVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testVideoTitleIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $vimeoVideo->getTitle());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testVideoDescriptionIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $vimeoVideo->getDescription());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('bool', $vimeoVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetThumbnailSizesIsArray($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('array', $vimeoVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfGetThumbnailIsString($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $vimeoVideo->getSmallThumbnail());
        $this->assertInternalType('string', $vimeoVideo->getMediumThumbnail());
        $this->assertInternalType('string', $vimeoVideo->getLargeThumbnail());
        $this->assertInternalType('string', $vimeoVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->setExpectedException('\\RicardoFiorani\\Adapter\\Exception\\InvalidThumbnailSizeException');
        $vimeoVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testGetEmbedUrl($url)
    {
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertInternalType('string', $vimeoVideo->getEmbedUrl());
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
        $vimeoVideo = $this->getMockingObject($url);
        $this->assertTrue($vimeoVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider()
    {
        return array(
            array(
                'https://vimeo.com/8733915',
                'https://vimeo.com/channels/staffpicks/154766467',
            ),
        );
    }

    /**
     * @param $url
     * @return VimeoServiceAdapter
     * @throws VideoServiceNotCompatibleException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceMatcher();
        $vimeoVideo = $videoParser->parse($url);

        return $vimeoVideo;
    }
}
