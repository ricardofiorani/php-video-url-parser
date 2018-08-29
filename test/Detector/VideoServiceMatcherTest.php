<?php

namespace RicardoFiorani\Test\Detector;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Container\ServicesContainer;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Matcher\Exception\VideoServiceNotCompatibleException;
use Zend\Diactoros\Uri;

class VideoServiceMatcherTest extends TestCase
{
    /**
     * @dataProvider videoUrlProvider
     * @param $url
     * @param $expectedServiceName
     * @throws VideoServiceNotCompatibleException
     */
    public function testCanParseUrl($url, $expectedServiceName)
    {
        $detector = new VideoServiceMatcher();
        $video = $detector->parse($url);
        $this->assertInstanceOf($expectedServiceName, $video);
    }

    /**
     * @return array
     */
    public function videoUrlProvider()
    {

        return array(
            'Normal Youtube URL' => array(
                'https://www.youtube.com/watch?v=mWRsgZuwf_8',
                YoutubeServiceAdapter::class,
            ),
            'Short Youtube URL' => array(
                'https://youtu.be/JMLBOKVfHaA',
                YoutubeServiceAdapter::class,
            ),
            'Embed Youtube URL' => array(
                '<iframe width="420" height="315" src="https://www.youtube.com/embed/vwp9JkaESdg" frameborder="0" allowfullscreen></iframe>',
                YoutubeServiceAdapter::class,
            ),
            'Common Vimeo URL' => array(
                'https://vimeo.com/137781541',
                VimeoServiceAdapter::class,
            ),
            'Commom Dailymotion URL' => array(
                'http://www.dailymotion.com/video/x332a71_que-categoria-jogador-lucas-lima-faz-golaco-em-treino-do-santos_sport',
                DailymotionServiceAdapter::class,
            ),
            'Commom Facebook Video URL' => array(
                'https://www.facebook.com/RantPets/videos/583336855137988/',
                FacebookServiceAdapter::class,
            )
        );
    }

    /**
     * @throws VideoServiceNotCompatibleException
     * @dataProvider invalidVideoUrlProvider
     */
    public function testThrowsExceptionOnInvalidUrl($url)
    {
        $detector = new VideoServiceMatcher();
        $this->expectException(VideoServiceNotCompatibleException::class);
        $video = $detector->parse($url);
    }

    /**
     * @return array
     */
    public function invalidVideoUrlProvider()
    {
        return array(
            array(
                'http://tvuol.uol.com.br/video/dirigindo-pelo-mundo-de-final-fantasy-xv-0402CC9B3764E4A95326',
            ),
            array(
                'https://www.google.com.br/',
            ),
            array(
                'https://www.youtube.com/',
            ),
        );
    }

    /**
     * @dataProvider videoUrlProvider
     * @param $url
     * @throws VideoServiceNotCompatibleException
     */
    public function testServiceDetectorDontReparseSameUrl($url)
    {
        $detector = new VideoServiceMatcher();
        $video = $detector->parse($url);

        $this->assertSame($video, $detector->parse($url));
    }

    /**
     * Tests container getter
     */
    public function testServiceContainerGetter()
    {
        $detector = new VideoServiceMatcher();
        $this->assertInstanceOf(ServicesContainer::class, $detector->getServiceContainer());
    }

    /**
     * Tests container setter
     */
    public function testServiceContainerSetter()
    {
        $detector = new VideoServiceMatcher();
        $serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
        $detector->setServiceContainer($serviceContainer);
        $this->assertSame($serviceContainer, $detector->getServiceContainer());
    }

    public function testCanParsePsr7Uri()
    {
        $detector = new VideoServiceMatcher();
        $video = $detector->parse(new Uri('https://www.youtube.com/watch?v=PkOcm_XaWrw'));
        $this->assertInstanceOf(VideoAdapterInterface::class, $video);
    }
}
