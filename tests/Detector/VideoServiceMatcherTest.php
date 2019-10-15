<?php declare(strict_types=1);

namespace RicardoFioraniTests\Detector;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Container\Factory\ServicesContainerFactory;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Exception\ServiceNotAvailableException;
use RicardoFiorani\Adapter\Youtube\YoutubeServiceAdapter;
use RicardoFiorani\Adapter\Vimeo\VimeoServiceAdapter;
use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;

class VideoServiceMatcherTest extends TestCase
{
    /**
     * @dataProvider videoUrlProvider
     */
    public function testCanParseUrl(string $url, string $expectedServiceName): void
    {
        $detector = new VideoServiceMatcher();
        $video = $detector->parse($url);
        static::assertEquals($expectedServiceName, get_class($video));
    }

    /**
     * @return array
     */
    public function videoUrlProvider(): array
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
                '<iframe width="420" height="315" src="https://www.youtube.com/embed/vwp9JkaESdg" frameborder="0" allowfullscreen></iframe>',
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
     * @throws ServiceNotAvailableException
     * @dataProvider invalidVideoUrlProvider
     */
    public function testThrowsExceptionOnInvalidUrl($url): void
    {
        $detector = new VideoServiceMatcher();
        $this->expectException(ServiceNotAvailableException::class);
        $video = $detector->parse($url);
    }

    /**
     * @return array
     */
    public function invalidVideoUrlProvider(): array
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
     * @throws ServiceNotAvailableException
     */
    public function testServiceDetectorDontReparseSameUrl(string $url): void
    {
        $detector = new VideoServiceMatcher();
        $video = $detector->parse($url);

        static::assertSame($video, $detector->parse($url));
    }

    /**
     * Tests container setter
     */
    public function testServiceContainerSetter(): void
    {
        $detector = new VideoServiceMatcher();
        $serviceContainer = ServicesContainerFactory::createNewServiceMatcher();
        $detector->setServiceContainer($serviceContainer);
        static::assertSame($serviceContainer, $detector->getServiceContainer());
    }

}
