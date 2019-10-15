<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Vimeo;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Exception\ServiceApiNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class VimeoServiceAdapter extends AbstractServiceAdapter
{
    public const THUMBNAIL_SMALL = 'thumbnail_small';
    public const THUMBNAIL_MEDIUM = 'thumbnail_medium';
    public const THUMBNAIL_LARGE = 'thumbnail_large';
    public const SERVICE_NAME = 'Vimeo';

    public string $title;

    public string $description;

    public array $thumbnails;

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = (string)$this->getVideoIdByPattern($url, $pattern);
        $this->setVideoId($videoId);
        $videoData = $this->getVideoDataFromServiceApi();

        $this->setThumbnails([
            self::THUMBNAIL_SMALL => $videoData[self::THUMBNAIL_SMALL],
            self::THUMBNAIL_MEDIUM => $videoData[self::THUMBNAIL_MEDIUM],
            self::THUMBNAIL_LARGE => $videoData[self::THUMBNAIL_LARGE],
        ]);

        $this->setTitle($videoData['title']);
        $this->setDescription($videoData['description']);

        parent::__construct($url, $pattern, $renderer);
    }

    public function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    public function hasThumbnail(): bool
    {
        return false === empty($this->thumbnails);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    private function setThumbnails(array $thumbnails): void
    {
        foreach ($thumbnails as $key => $thumbnail) {
            $this->thumbnails[$key] = parse_url($thumbnail);
        }
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false === in_array($size, $this->getThumbNailSizes(), false)) {
            throw new InvalidThumbnailSizeException("The size {$size} is invalid.");
        }

        return sprintf(
            '%s://%s%s',
            $this->getScheme($forceSecure),
            $this->thumbnails[$size]['host'],
            $this->thumbnails[$size]['path']
        );
    }

    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        return $this->getScheme($forceSecure) . '://player.vimeo.com/video/' . $this->getVideoId() . ($forceAutoplay ? '?autoplay=1' : '');
    }

    public function getThumbNailSizes(): array
    {
        return [
            self::THUMBNAIL_SMALL,
            self::THUMBNAIL_MEDIUM,
            self::THUMBNAIL_LARGE,
        ];
    }

    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_SMALL, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE, $forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE, $forceSecure);
    }

    public function isEmbeddable(): bool
    {
        return true;
    }

    private function getVideoIdByPattern(string $url, string $pattern): int
    {
        $match = [];
        preg_match($pattern, $url, $match);

        return (int)$match[2];
    }

    /**
     * @throws ServiceApiNotAvailable
     */
    private function getVideoDataFromServiceApi(): array
    {
        $contents = file_get_contents('http://vimeo.com/api/v2/video/' . $this->getVideoId() . '.php');
        if (false === $contents) {
            throw new ServiceApiNotAvailable('Vimeo Service Adapter could not reach Vimeo API Service. Check if your server has file_get_contents() function available.');
        }
        $hash = unserialize($contents);

        return reset($hash);
    }
}
