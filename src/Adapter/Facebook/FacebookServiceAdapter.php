<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Facebook;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Exception\ThumbnailSizeNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class FacebookServiceAdapter extends AbstractServiceAdapter
{
    public const THUMBNAIL_SIZE_DEFAULT = 'default';
    public const SERVICE_NAME = 'Facebook';

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $match = [];
        preg_match($pattern, $url, $match);
        $this->setVideoId($match[1]);

        parent::__construct($url, $pattern, $renderer);
    }

    public function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    public function hasThumbnail(): bool
    {
        return true;
    }

    public function getThumbNailSizes(): array
    {
        return [self::THUMBNAIL_SIZE_DEFAULT];
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false === in_array($size, $this->getThumbNailSizes(), false)) {
            throw new InvalidThumbnailSizeException("{$size} is not a valid size");
        }

        return $this->getScheme($forceSecure) . '://graph.facebook.com/' . $this->getVideoId() . '/picture';
    }

    /**
     * @throws ThumbnailSizeNotAvailable
     */
    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        throw new ThumbnailSizeNotAvailable('The smallest size thumbnail is not supported');
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT, $forceSecure);
    }

    /**
     * @throws ThumbnailSizeNotAvailable
     */
    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        throw new ThumbnailSizeNotAvailable('The large size thumbnail is not supported');
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT, $forceSecure);
    }

    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        return $this->getScheme($forceSecure) . '://www.facebook.com/video/embed?video_id=' . $this->getVideoId();
    }

    public function isEmbeddable(): bool
    {
        return true;
    }
}
