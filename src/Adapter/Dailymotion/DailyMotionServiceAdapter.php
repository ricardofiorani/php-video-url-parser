<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 14:38
 */

namespace RicardoFiorani\Adapter\Dailymotion;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailyMotionServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'thumbnail';

    /**
     * AbstractVideoAdapter constructor.
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
    {
        $videoId = strtok(basename($url), '_');
        $this->setVideoId($videoId);

        return parent::__construct($url, $pattern, $renderer);
    }


    /**
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName()
    {
        return 'DailyMotion';
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
     * Returns all thumbnails available sizes
     * @return array
     */
    public function getThumbNailSizes()
    {
        return array(
            self::THUMBNAIL_DEFAULT,
        );
    }

    /**
     * @param string $size
     * @return string
     */
    public function getThumbnail($size)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new \RicardoFiorani\Exception\InvalidThumbnailSizeException;
        }

        return 'http://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * Returns the small thumbnail's url
     * @return string
     */
    public function getSmallThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the medium thumbnail's url
     * @return string
     */
    public function getMediumThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the large thumbnail's url
     * @return string
     */
    public function getLargeThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * Returns the largest thumnbnaail's url
     * @return string
     */
    public function getLargestThumbnail()
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT);
    }

    /**
     * @param bool $autoplay
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return '//www.dailymotion.com/embed/video/' . $this->videoId . ($autoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}