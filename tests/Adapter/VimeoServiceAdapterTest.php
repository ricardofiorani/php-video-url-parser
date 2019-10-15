<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;

class VimeoServiceAdapterTest extends TestCase
{
    use VideoMatcherTrait;

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsString($vimeoVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testVideoTitleIsString(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsString($vimeoVideo->getTitle());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testVideoDescriptionIsString(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsString($vimeoVideo->getDescription());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     * @param string $url
     */
    public function testHasThumbnailIsBoolean(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsBool($vimeoVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsArray($vimeoVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsString($vimeoVideo->getSmallThumbnail());
        static::assertIsString($vimeoVideo->getMediumThumbnail());
        static::assertIsString($vimeoVideo->getLargeThumbnail());
        static::assertIsString($vimeoVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $vimeoVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetEmbedUrl(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertIsString($vimeoVideo->getEmbedUrl());
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
     */
    public function testIfIsEmbeddable(string $url): void
    {
        $vimeoVideo = $this->parse($url);
        static::assertTrue($vimeoVideo->isEmbeddable());
    }

    public function exampleUrlDataProvider(): array
    {
        return [
            [
                'https://vimeo.com/8733915',
                'https://vimeo.com/channels/staffpicks/154766467',
            ],
        ];
    }
}
