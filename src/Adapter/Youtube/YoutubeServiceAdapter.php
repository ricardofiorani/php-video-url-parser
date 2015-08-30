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

class YoutubeServiceAdapter extends AbstractServiceAdapter
{

    const THUMBNAIL_DEFAULT = 'default';
    const THUMBNAIL_HIGH_QUALITY = 'hqdefault';
    const THUMBNAIL_MEDIUM_QUALITY = 'mqdefault';
    const THUMBNAIL_STANDARD_DEFINITION = 'sddefault';
    const THUMBNAIL_MAX_QUALITY = 'maxresdefault';


    public function __construct($url, $pattern)
    {
        preg_match($pattern, $url, $match);
        $videoId = $match[2];
        if (empty($videoId)) {
            $videoId = $match[1];
        }
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern);
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
}
