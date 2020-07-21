<?php declare(strict_types=1);

namespace RicardoFiorani\Tests\VideoUrlParser\Detector;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\VideoUrlParser\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\VideoUrlParser\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\VideoUrlParser\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\VideoUrlParser\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\VideoUrlParser\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\VideoUrlParser\Container\ServicesContainer;
use RicardoFiorani\VideoUrlParser\Matcher\VideoServiceMatcher;
use RicardoFiorani\VideoUrlParser\Exception\ServiceNotAvailableException;

class VideoServiceMatcherTest extends TestCase
{
    /**
     * @dataProvider videoUrlProvider
     */
    public function testCanParseUrl(string $url, string $expectedServiceName)
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
        return [
            'Normal Youtube URL' => [
                'https://www.youtube.com/watch?v=mWRsgZuwf_8',
                YoutubeServiceAdapter::class,
            ],
            'Short Youtube URL' => [
                'https://youtu.be/JMLBOKVfHaA',
                YoutubeServiceAdapter::class,
            ],
            'Embed Youtube URL' => [
                '<iframe width="420" height="315" src="https://www.youtube.com/embed/vwp9JkaESdg" allowfullscreen></iframe>',
                YoutubeServiceAdapter::class,
            ],
            'Common Vimeo URL' => [
                'https://vimeo.com/137781541',
                VimeoServiceAdapter::class,
            ],
            'Commom Dailymotion URL' => [
                'http://www.dailymotion.com/video/x332a71_que-categoria-jogador-lucas-lima-faz-golaco-em-treino-do-santos_sport',
                DailymotionServiceAdapter::class,
            ],
            'Commom Facebook Video URL' => [
                'https://www.facebook.com/RantPets/videos/583336855137988/',
                FacebookServiceAdapter::class,
            ]
        ];
    }

    /**
     * @dataProvider invalidVideoUrlProvider
     */
    public function testThrowsExceptionOnInvalidUrl(string $url)
    {
        $detector = new VideoServiceMatcher();
        $this->expectException(ServiceNotAvailableException::class);
        $detector->parse($url);
    }

    /**
     * @return array
     */
    public function invalidVideoUrlProvider()
    {
        return [
            [
                'http://tvuol.uol.com.br/video/dirigindo-pelo-mundo-de-final-fantasy-xv-0402CC9B3764E4A95326',
            ],
            [
                'https://www.google.com.br/',
            ],
            [
                'https://www.youtube.com/',
            ],
        ];
    }

    /**
     * @dataProvider videoUrlProvider
     * @param $url
     * @throws ServiceNotAvailableException
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

}
