<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 14:38.
 */
namespace RicardoFiorani\Adapter\Dailymotion;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class DailymotionServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_DEFAULT = 'thumbnail';

    /**
     * AbstractVideoAdapter constructor.
     *
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
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName()
    {
        return 'Dailymotion';
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
     * Returns all thumbnails available sizes.
     *
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
     * @param bool $secure
     *
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $secure = false)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($secure) . '://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @param bool $secure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getSmallThumbnail($secure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $secure);
    }

    /**
     * Returns the medium thumbnail's url.
     *
     * @param bool $secure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail($secure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $secure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $secure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail($secure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $secure);
    }

    /**
     * Returns the largest thumnbnaail's url.
     * @param bool $secure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($secure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $secure);
    }

    /**
     * @param bool $autoplay
     * @param bool $secure
     * @return string
     */
    public function getEmbedUrl($autoplay = false, $secure = false)
    {
        return $this->getScheme($secure) . '://www.dailymotion.com/embed/video/' . $this->videoId . ($autoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
