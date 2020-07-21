<?php declare(strict_types = 1);
namespace RicardoFiorani\VideoUrlParser\Adapter\Dailymotion;

use RicardoFiorani\VideoUrlParser\Adapter\AbstractServiceAdapter;
use RicardoFiorani\VideoUrlParser\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\VideoUrlParser\Renderer\EmbedRendererInterface;

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
        return [
            self::THUMBNAIL_DEFAULT,
        ];
    }

    /**
     * @param string $size
     * @param bool $forceSecure
     *
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $forceSecure = false)
    {
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->getScheme($forceSecure) . '://www.dailymotion.com/' . $size . '/video/' . $this->videoId;
    }

    /**
     * Returns the small thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getSmallThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
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
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * Returns the largest thumnbnaail's url.
     * @param bool $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($forceSecure = false)
    {
        //Since this service does not provide other thumbnails sizes we just return the default size
        return $this->getThumbnail(self::THUMBNAIL_DEFAULT, $forceSecure);
    }

    /**
     * @param bool $forceAutoplay
     * @param bool $forceSecure
     * @return string
     */
    public function getEmbedUrl($forceAutoplay = false, $forceSecure = false)
    {
        return $this->getScheme($forceSecure) . '://www.dailymotion.com/embed/video/' . $this->videoId . ($forceAutoplay ? '?amp&autoplay=1' : '');
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }
}
