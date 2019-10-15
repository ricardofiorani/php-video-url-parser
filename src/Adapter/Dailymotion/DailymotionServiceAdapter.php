<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Dailymotion;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailymotionServiceAdapter extends AbstractServiceAdapter
{
    public const THUMBNAIL_DEFAULT = 'thumbnail';
    public const SERVICE_NAME = 'Dailymotion';

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = strtok(basename($url), '_');
        $this->setVideoId($videoId);

        parent::__construct($url, $pattern, $renderer);
    }

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    /**
     * Returns if the service has a thumbnail image.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return true;
    }

    /**
     * Returns all thumbnails available sizes.
     *
     * @return array
     */
    public function getThumbNailSizes(): array
    {
        return [
            self::THUMBNAIL_DEFAULT,
        ];
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false === in_array($size, $this->getThumbNailSizes(), false)) {
            throw new InvalidThumbnailSizeException("{$size} is not a valid size.");
        }

        return $this->getScheme($forceSecure) . '://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail(bool $forceSecure = false): string
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        $scheme = $this->getScheme($forceSecure);
        $autoPlay = ($forceAutoplay ? '?amp&autoplay=1' : '');
        return <<<STRING
{$scheme}://www.dailymotion.com/embed/video/{$this->videoId}{$autoPlay}
STRING;
    }

    public function isEmbeddable(): bool
    {
        return true;
    }
}
