<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Adapter\Facebook\FacebookServiceAdapter;
use RicardoFiorani\Exception\ThumbnailSizeNotAvailable;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;

class FacebookServiceAdapterTest extends TestCase
{
    use VideoMatcherTrait;

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString(string $url): void
    {
        $facebookVideo = $this->parse($url);
        static::assertEquals(FacebookServiceAdapter::SERVICE_NAME, $facebookVideo->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testHasThumbnailIsBoolean(string $url): void
    {
        $facebookVideo = $this->parse($url);
        static::assertIsBool($facebookVideo->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray(string $url)
    {
        $facebookVideo = $this->parse($url);
        static::assertIsArray($facebookVideo->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString(string $url)
    {
        $facebookVideo = $this->parse($url);
        static::assertNotEmpty($facebookVideo->getThumbnail(FacebookServiceAdapter::THUMBNAIL_SIZE_DEFAULT));
        static::assertNotEmpty($facebookVideo->getMediumThumbnail());
        static::assertNotEmpty($facebookVideo->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $facebookVideo->getThumbnail('This Size does not exists :)');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetSmallThumbnailThrowsException(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $this->expectException(ThumbnailSizeNotAvailable::class);
        $facebookVideo->getSmallThumbnail();
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetLargeThumbnailThrowsException(string $url): void
    {
        $facebookVideo = $this->parse($url);
        $this->expectException(ThumbnailSizeNotAvailable::class);
        $facebookVideo->getLargeThumbnail();
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfEmbedUrlIsString(string $url): void
    {
        $facebookVideo = $this->parse($url);
        static::assertNotEmpty($facebookVideo->getEmbedUrl());
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
        $facebookVideo = $this->parse($url);
        static::assertTrue($facebookVideo->isEmbeddable());
    }

    public function exampleUrlDataProvider(): array
    {
        return [
            [
                'https://www.facebook.com/zuck/videos/10102367711349271'
            ],
        ];
    }
}
