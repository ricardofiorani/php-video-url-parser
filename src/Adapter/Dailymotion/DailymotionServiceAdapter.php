<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Dailymotion;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Adapter\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailymotionServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'thumbnail';

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = strtok(basename($url), '_');
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern, $renderer);
    }

    public function getServiceName(): string
    {
        return 'Dailymotion';
    }

    public function hasThumbnail(): bool
    {
        return true;
    }

    public function getThumbNailSizes(): array
    {
        return array(
            self::THUMBNAIL_DEFAULT,
        );
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($forceSecure) . '://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getLargestThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws \RicardoFiorani\Adapter\Exception\InvalidUrlException
     */
    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        return $this->getScheme($forceSecure) . '://www.dailymotion.com/embed/video/' . $this->videoId . ($forceAutoplay ? '?amp&autoplay=1' : '');
    }

    public function isEmbeddable(): bool
    {
        return true;
    }
}
