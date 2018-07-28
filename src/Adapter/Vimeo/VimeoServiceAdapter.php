<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Vimeo;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Adapter\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Adapter\Exception\InvalidUrlException;
use RicardoFiorani\Adapter\Exception\ServiceApiNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class VimeoServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_SMALL = 'thumbnail_small';
    const THUMBNAIL_MEDIUM = 'thumbnail_medium';
    const THUMBNAIL_LARGE = 'thumbnail_large';

    public $title;
    public $description;
    public $thumbnails;

    /**
     * @throws ServiceApiNotAvailable
     */
    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = $this->getVideoIdByPattern($url, $pattern);
        $this->setVideoId($videoId);
        $videoData = $this->getVideoDataFromServiceApi();

        $this->setThumbnails(array(
            self::THUMBNAIL_SMALL => $videoData[self::THUMBNAIL_SMALL],
            self::THUMBNAIL_MEDIUM => $videoData[self::THUMBNAIL_MEDIUM],
            self::THUMBNAIL_LARGE => $videoData[self::THUMBNAIL_LARGE],
        ));

        $this->setTitle($videoData['title']);
        $this->setDescription($videoData['description']);

        return parent::__construct($url, $pattern, $renderer);
    }

    public function getServiceName(): string
    {
        return 'Vimeo';
    }

    public function hasThumbnail(): bool
    {
        return false == empty($this->thumbnails);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    private function setThumbnails(array $thumbnails)
    {
        foreach ($thumbnails as $key => $thumbnail) {
            $this->thumbnails[$key] = parse_url($thumbnail);
        }
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws InvalidUrlException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return sprintf(
            '%s://%s%s',
            $this->getScheme($forceSecure),
            $this->thumbnails[$size]['host'],
            $this->thumbnails[$size]['path']
        );
    }

    /**
     * @throws InvalidUrlException
     */
    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        return $this->getScheme($forceSecure) . '://player.vimeo.com/video/' . $this->getVideoId() . ($forceAutoplay ? '?autoplay=1' : '');
    }

    public function getThumbNailSizes(): array
    {
        return array(
            self::THUMBNAIL_SMALL,
            self::THUMBNAIL_MEDIUM,
            self::THUMBNAIL_LARGE,
        );
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws InvalidUrlException
     */
    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_SMALL,$forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws InvalidUrlException
     */
    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM,$forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws InvalidUrlException
     */
    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE,$forceSecure);
    }

    /**
     * @throws InvalidThumbnailSizeException
     * @throws InvalidUrlException
     */
    public function getLargestThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE,$forceSecure);
    }

    public function isEmbeddable(): string
    {
        return true;
    }

    private function getVideoIdByPattern(string $url, string $pattern): int
    {
        $match = array();
        preg_match($pattern, $url, $match);
        $videoId = $match[2];

        return $videoId;
    }

    /**
     * TODO make this better by using guzzle
     * @throws ServiceApiNotAvailable
     */
    private function getVideoDataFromServiceApi(): array
    {
        $contents = file_get_contents('http://vimeo.com/api/v2/video/' . $this->getVideoId() . '.php');
        if (false === $contents) {
            throw new ServiceApiNotAvailable(
                'Service "%s" could not reach it\'s API. Check if file_get_contents() function is available.',
                $this->getServiceName()
            );
        }
        $hash = unserialize($contents);

        return reset($hash);
    }
}
