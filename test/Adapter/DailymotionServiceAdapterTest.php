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
use RicardoFiorani\Detector\VideoServiceDetector;
use RicardoFiorani\Exception\ServiceNotAvailableException;

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
        $this->setExpectedException('\\RicardoFiorani\\Exception\\InvalidThumbnailSizeException');
        $dailymotionVideo->getThumbnail('This Size does not exists :)');
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
     * @throws ServiceNotAvailableException
     */
    public function getMockingObject($url)
    {
        $videoParser = new VideoServiceDetector();
        $dailymotionVideo = $videoParser->parse($url);

        return $dailymotionVideo;
    }

}