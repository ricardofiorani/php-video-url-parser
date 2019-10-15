<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;

class YoutubeServiceAdapterTest extends TestCase
{
    use VideoMatcherTrait;

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        static::assertIsString($youtubeVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testHasThumbnailIsBoolean(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        static::assertIsBool($youtubeVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        static::assertIsArray($youtubeVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        static::assertIsString($youtubeVideo->getSmallThumbnail());
        static::assertIsString($youtubeVideo->getMediumThumbnail());
        static::assertIsString($youtubeVideo->getLargeThumbnail());
        static::assertIsString($youtubeVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $youtubeVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfEmbedUrlIsString(string $url): void
    {
        $youtubeVideo = $this->parse($url);
        $this->assertIsString($youtubeVideo->getEmbedUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetEmbedUrlUsesRightScheme(string $url): void
    {
        $videoObject = $this->parse($url);
        $embedUrl = $videoObject->getEmbedUrl(false, true);
        static::assertStringContainsString('https', $embedUrl);

        $embedUrl = $videoObject->getEmbedUrl(false, false);
        static::assertEquals(parse_url($url, PHP_URL_SCHEME), parse_url($embedUrl, PHP_URL_SCHEME));
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testIfIsEmbeddable(string $url): void
    {
        $facebookVideo = $this->parse($url);
        static::assertTrue($facebookVideo->isEmbeddable());
    }

    /**
     * @return array
     */
    public function exampleUrlDataProvider(): array
    {
        return [
            [
                'https://www.youtube.com/watch?v=uarCDXc3BjU',
                'https://youtu.be/uarCDXc3BjU',
                'http://youtu.be/uarBDXc3BjU',
                '<iframe width="560" height="315" src="https://www.youtube.com/embed/uarCDXc3BjU" frameborder="0" allowfullscreen></iframe>',
            ],
        ];
    }
}
