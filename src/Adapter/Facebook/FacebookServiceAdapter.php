<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 02/09/2015
 * Time: 22:42.
 */
namespace RicardoFiorani\Adapter\Facebook;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Exception\ThumbnailSizeNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class FacebookServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_SIZE_DEFAULT = 'default';

    /**
     * AbstractVideoAdapter constructor.
     *
     * @param string                 $url
     * @param string                 $pattern
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
     * Returns the service name (ie: "Youtube" or "Vimeo").
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
     * @return string
     *
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return 'https://graph.facebook.com/'.$this->getVideoId().'/picture';
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @return string
     *
     * @throws ThumbnailSizeNotAvailable
     */
    public function getSmallThumbnail()
    {
        throw new ThumbnailSizeNotAvailable();
    }

    /**
     * Returns the medium thumbnail's url.
     *
     * @return string
     */
    public function getMediumThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @return string
     *
     * @throws ThumbnailSizeNotAvailable
     */
    public function getLargeThumbnail()
    {
        throw new ThumbnailSizeNotAvailable();
    }

    /**
     * Returns the largest thumnbnaail's url.
     *
     * @return string
     */
    public function getLargestThumbnail()
    {
        return $this->getThumbnail(self::THUMBNAIL_SIZE_DEFAULT);
    }

    /**
     * @param bool $autoplay
     *
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return 'https://www.facebook.com/video/embed?video_id='.$this->getVideoId();
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
