<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:53
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
        $videoId = $match[2];
        if (empty($videoId)) {
            $videoId = $match[1];
        }
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern, $renderer);
    }

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName()
    {
        return 'Youtube';
    }

    /**
     * Returns if the service has a thumbnail image
     * @return bool
     */
    public function hasThumbnail()
    {
        return true;
    }

    /**
     * @param string $size
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return 'http://img.youtube.com/vi/' . $this->getVideoId() . '/' . $size . '.jpg';
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
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return 'http://www.youtube.com/embed/' . $this->getVideoId() . ($autoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * Returns the small thumbnail's url
     * @return string
     */
    public function getSmallThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_STANDARD_DEFINITION);
    }

    /**
     * Returns the medium thumbnail's url
     * @return string
     */
    public function getMediumThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM_QUALITY);
    }

    /**
     * Returns the large thumbnail's url
     * @return string
     */
    public function getLargeThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_HIGH_QUALITY);
    }

    /**
     * Returns the largest thumnbnaail's url
     * @return string
     */
    public function getLargestThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_MAX_QUALITY);
    }


    /**
     * @return bool
     */
    public function isEmbedable()
    {
        return true;
    }
}
