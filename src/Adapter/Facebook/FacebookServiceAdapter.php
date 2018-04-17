<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 02/09/2015
 * Time: 22:42.
 */
namespace RicardoFiorani\Adapter\Facebook;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Adapter\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Exception\ThumbnailSizeNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class FacebookServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_SIZE_DEFAULT = 'default';

    /**
     * AbstractVideoAdapter constructor.
     *
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
    {
        $match = array();
        preg_match($pattern, $url, $match);
        $this->setVideoId($match[1]);

        return parent::__construct($url, $pattern, $renderer);
    }

    /**
     * Returns the service name .
     *
     * @return string
     */
    public function getServiceName()
    {
        return 'Facebook';
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
        return array(self::THUMBNAIL_SIZE_DEFAULT);
    }

    /**
     * @param string $size
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $forceSecure = false)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($forceSecure) . '://graph.facebook.com/' . $this->getVideoId() . '/picture';
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws ThumbnailSizeNotAvailable
     */
    public function getSmallThumbnail($forceSecure = false)
    {
        throw new ThumbnailSizeNotAvailable();
    }

    /**
     * Returns the medium thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getMediumThumbnail($forceSecure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT, $forceSecure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws ThumbnailSizeNotAvailable
     */
    public function getLargeThumbnail($forceSecure = false)
    {
        throw new ThumbnailSizeNotAvailable();
    }

    /**
     * Returns the largest thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($forceSecure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT, $forceSecure);
    }

    /**
     * @param bool $forceAutoplay
     *
     * @param bool $forceSecure
     * @return string
     */
    public function getEmbedUrl($forceAutoplay = false, $forceSecure = false)
    {
        return $this->getScheme($forceSecure) . '://www.facebook.com/video/embed?video_id=' . $this->getVideoId();
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
