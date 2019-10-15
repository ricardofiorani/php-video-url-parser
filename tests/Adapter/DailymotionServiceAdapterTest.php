<?php declare(strict_types=1);

namespace RicardoFioraniTests\Adapter;

use PHPUnit\Framework\TestCase;
use RicardoFiorani\Adapter\Dailymotion\DailymotionServiceAdapter;
use RicardoFiorani\Adapter\VideoAdapterInterface;
use RicardoFiorani\Matcher\VideoServiceMatcher;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;

class DailymotionServiceAdapterTest extends TestCase
{
    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testServiceNameIsString(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertEquals(DailymotionServiceAdapter::SERVICE_NAME, $adapter->getServiceName());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testHasThumbnailIsBoolean(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertTrue($adapter->hasThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testGetThumbnailSizesIsArray(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertIsArray($adapter->getThumbNailSizes());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetThumbnailIsString(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertNotEmpty($adapter->getThumbnail(DailymotionServiceAdapter::THUMBNAIL_DEFAULT));
        static::assertNotEmpty($adapter->getSmallThumbnail());
        static::assertNotEmpty($adapter->getMediumThumbnail());
        static::assertNotEmpty($adapter->getLargeThumbnail());
        static::assertNotEmpty($adapter->getLargestThumbnail());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testThrowsExceptionOnRequestThumbnailWithAnInvalidSize(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        $this->expectException(InvalidThumbnailSizeException::class);
        $adapter->getThumbnail('SIZE_THAT_DOESNT_EXIST');
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetEmbedUrl(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertNotEmpty($adapter->getEmbedUrl());
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfGetEmbedUrlUsesRightScheme(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        $embedUrl = $adapter->getEmbedUrl(false, true);
        static::assertStringContainsString('https', $embedUrl);

        $embedUrl = $adapter->getEmbedUrl(false, false);
        static::assertEquals(parse_url($url, PHP_URL_SCHEME), parse_url($embedUrl, PHP_URL_SCHEME));
    }

    /**
     * @dataProvider exampleUrlDataProvider
     */
    public function testIfIsEmbeddable(string $url): void
    {
        $adapter = $this->getMockingObject($url);
        static::assertTrue($adapter->isEmbeddable());
    }

    public function exampleUrlDataProvider(): array
    {
        return [
            [
                'http://www.dailymotion.com/video/x332a71_que-categoria-jogador-lucas-lima-faz-golaco-em-treino-do-santos_sport'
            ],
        ];
    }

    public function getMockingObject(string $url): VideoAdapterInterface
    {
        $videoParser = new VideoServiceMatcher();

        return $videoParser->parse($url);
    }

}
