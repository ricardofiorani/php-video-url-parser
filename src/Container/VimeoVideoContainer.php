<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56
 */

namespace RicardoFiorani\Container;


use RicardoFiorani\Adapter\VideoAdapterInterface;

class VimeoVideoContainer implements VideoAdapterInterface
{

    const THUMBNAIL_SMALL = 'thumbnail_small';
    const THUMBNAIL_MEDIUM = 'thumbnail_medium';
    const THUMBNAIL_LARGE = 'thumbnail_large';


    /**
     * @var string
     */
    public $rawUrl;

    /**
     * @var string
     */
    public $videoId;

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
     * @param string $rawUrl
     */
    public function __construct($rawUrl)
    {
        $this->setRawUrl($rawUrl);
    }

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName()
    {
        return 'Vimeo';
    }

    /**
     * Returns the input URL
     * @return string
     */
    public function getRawUrl()
    {
        return $this->rawUrl;
    }

    /**
     * Returns if the service has a thumbnail image
     * @return bool
     */
    public function hasThumbnail()
    {
        return false == empty($this->thumbnails);
    }

    /**
     * @param string $rawUrl
     */
    public function setRawUrl($rawUrl)
    {
        $this->rawUrl = $rawUrl;
    }


    /**
     * @return string
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * @param string $videoId
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
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
     * @return array
     */
    public function getThumbnails()
    {
        return $this->thumbnails;
    }

    /**
     * @param array $thumbnails
     */
    public function setThumbnails($thumbnails)
    {
        $this->thumbnails = $thumbnails;
    }


    /**
     * @param string $size
     * @return string
     */
    public function getThumbnail($size)
    {
        return $this->thumbnails($size);
    }

    /**
     * @param bool $autoplay
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return "http://player.vimeo.com/video/$videoId?byline=0&amp;portrait=0&amp" . ($autoplay ? '&amp&autoplay=1' : '');
    }
}