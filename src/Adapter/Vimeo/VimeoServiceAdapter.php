<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56.
 */
namespace RicardoFiorani\Adapter\Vimeo;

use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;
use RicardoFiorani\Exception\ServiceApiNotAvailable;
use RicardoFiorani\Renderer\EmbedRendererInterface;

class VimeoServiceAdapter extends AbstractServiceAdapter
{
    const THUMBNAIL_SMALL = 'thumbnail_small';
    const THUMBNAIL_MEDIUM = 'thumbnail_medium';
    const THUMBNAIL_LARGE = 'thumbnail_large';

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var array
     */
    public $thumbnails;

    /**
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
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

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName()
    {
        return 'Vimeo';
    }

    /**
     * Returns if the service has a thumbnail image.
     *
     * @return bool
     */
    public function hasThumbnail()
    {
        return false == empty($this->thumbnails);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param array $thumbnails
     */
    private function setThumbnails(array $thumbnails)
    {
        foreach ($thumbnails as $key => $thumbnail) {
            $this->thumbnails[$key] = parse_url($thumbnail);
        }
    }

    /**
     * @param string $size
     *
     * @return string
     *
     * @throws InvalidThumbnailSizeException
     */
    public function getThumbnail($size, $forceSecure = false)
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
     * @param bool $forceAutoplay
     *
     * @return string
     */
    public function getEmbedUrl($forceAutoplay = false, $forceSecure = false)
    {
        return $this->getScheme($forceSecure) . '://player.vimeo.com/video/' . $this->getVideoId() . ($forceAutoplay ? '?autoplay=1' : '');
    }

    /**
     * Returns all thumbnails available sizes.
     *
     * @return array
     */
    public function getThumbNailSizes()
    {
        return array(
            self::THUMBNAIL_SMALL,
            self::THUMBNAIL_MEDIUM,
            self::THUMBNAIL_LARGE,
        );
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
        return $this->getThumbnail(self::THUMBNAIL_SMALL,$forceSecure);
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
        return $this->getThumbnail(self::THUMBNAIL_MEDIUM,$forceSecure);
    }

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $forceSecure
     * @param $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargeThumbnail($forceSecure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE,$forceSecure);
    }

    /**
     * Returns the largest thumnbnaail's url.
     *
     * @param bool $forceSecure
     * @param $forceSecure
     * @return string
     * @throws InvalidThumbnailSizeException
     */
    public function getLargestThumbnail($forceSecure = false)
    {
        return $this->getThumbnail(self::THUMBNAIL_LARGE,$forceSecure);
    }

    /**
     * @return bool
     */
    public function isEmbeddable()
    {
        return true;
    }

    /**
     * @param string $url
     * @param string $pattern
     *
     * @return int
     */
    private function getVideoIdByPattern($url, $pattern)
    {
        $match = array();
        preg_match($pattern, $url, $match);
        $videoId = $match[2];

        return $videoId;
    }

    /**
     * Uses the Vimeo video API to get video info.
     *
     * @todo make this better by using guzzle
     *
     * @return array
     *
     * @throws ServiceApiNotAvailable
     */
    private function getVideoDataFromServiceApi()
    {
        $contents = file_get_contents('http://vimeo.com/api/v2/video/' . $this->getVideoId() . '.php');
        if (false === $contents) {
            throw new ServiceApiNotAvailable('Vimeo Service Adapter could not reach Vimeo API Service. Check if your server has file_get_contents() function available.');
        }
        $hash = unserialize($contents);

        return reset($hash);
    }
}
