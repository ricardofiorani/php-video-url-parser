<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:53.
 */
namespace RicardoFiorani\Adapter\Youtube;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class YoutubeServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'default';
    const THUMBNAIL_STANDARD_DEFINITION = 'sddefault';
    const THUMBNAIL_MEDIUM_QUALITY = 'mqdefault';
    const THUMBNAIL_HIGH_QUALITY = 'hqdefault';
    const THUMBNAIL_MAX_QUALITY = 'maxresdefault';

    /**
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
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

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName()
    {
        return 'Youtube';
    }

    /**
     * Returns if the service has a thumbnail image.
     *
     * @return bool
     */
    public function hasThumbnail()
    {
        return true;
    }

    /**
     * @param string $size
     * @param bool $secure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $secure = false)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($secure) . '://img.youtube.com/vi/' . $this->getVideoId() . '/' . $size . '.jpg';
    }

    /**
     * @return array
     */
    public function getThumbNailSizes()
    {
        return array(
            self::THUMBNAIL_DEFAULT,
            self::THUMBNAIL_STANDARD_DEFINITION,
            self::THUMBNAIL_MEDIUM_QUALITY,
            self::THUMBNAIL_HIGH_QUALITY,
            self::THUMBNAIL_MAX_QUALITY,
        );
    }

    /**
     * @param bool $autoplay
     * @param bool $secure
     * @return string
     */
    public function getEmbedUrl($autoplay = false, $secure = false)
    {
        return $this->getScheme($secure) . '://www.youtube.com/embed/' . $this->getVideoId() . ($autoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @return string
     */
    public function getSmallThumbnail($secure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_STANDARD_DEFINITION, $secure);
    }

    /**
     * Returns the medium thumbnail's url.
     *
     * @return string
     */
    public function getMediumThumbnail($secure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM_QUALITY, $secure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @return string
     */
    public function getLargeThumbnail($secure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_HIGH_QUALITY, $secure);
    }

    /**
     * Returns the largest thumnbnaail's url.
     *
     * @return string
     */
    public function getLargestThumbnail($secure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_MAX_QUALITY, $secure);
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
