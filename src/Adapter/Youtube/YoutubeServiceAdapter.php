<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter\Youtube;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Adapter\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class YoutubeServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'default';
    const THUMBNAIL_STANDARD_DEFINITION = 'sddefault';
    const THUMBNAIL_MEDIUM_QUALITY = 'mqdefault';
    const THUMBNAIL_HIGH_QUALITY = 'hqdefault';
    const THUMBNAIL_MAX_QUALITY = 'maxresdefault';

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        preg_match($pattern, $url, $match);
        if (isset($match[2])) {
            $videoId = $match[2];
        }
        if (empty($videoId)) {
            $videoId = $match[1];
        }
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern, $renderer);
    }

    public function getServiceName(): string
    {
        return 'Youtube';
    }

    public function hasThumbnail(): bool
    {
        return true;
    }

    /**
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail(string $size, bool $forceSecure = false): string
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($forceSecure) . '://img.youtube.com/vi/' . $this->getVideoId() . '/' . $size . '.jpg';
    }

    public function getThumbNailSizes(): array
    {
        return array(
            self::THUMBNAIL_DEFAULT,
            self::THUMBNAIL_STANDARD_DEFINITION,
            self::THUMBNAIL_MEDIUM_QUALITY,
            self::THUMBNAIL_HIGH_QUALITY,
            self::THUMBNAIL_MAX_QUALITY,
        );
    }

    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string
    {
        $queryString = $this->generateQuerystring($forceAutoplay);

        return sprintf(
            '%s://www.youtube.com/embed/%s?%s',
            $this->getScheme($forceSecure),
            $this->getVideoId(),
            http_build_query($queryString)
        );
    }

    public function getSmallThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_STANDARD_DEFINITION, $forceSecure);
    }

    public function getMediumThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM_QUALITY, $forceSecure);
    }

    public function getLargeThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_HIGH_QUALITY, $forceSecure);
    }

    public function getLargestThumbnail(bool $forceSecure = false): string
    {
        return $this->getThumbnail(self::THUMBNAIL_MAX_QUALITY, $forceSecure);
    }

    public function isEmbeddable(): bool
    {
        return true;
    }

    private function generateQuerystring(bool $forceAutoplay = false): array
    {
        parse_str(parse_url($this->rawUrl, PHP_URL_QUERY), $queryString);
        unset($queryString['v']);

        if ($forceAutoplay) {
            $queryString['autoplay'] = (int) $forceAutoplay;
        }

        return $queryString;
    }
}
