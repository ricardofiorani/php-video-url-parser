<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56
 */

namespace RicardoFiorani\Adapter\Vimeo;


use RicardoFiorani\Adapter\AbstractServiceAdapter;
use RicardoFiorani\Exception\InvalidThumbnailSizeException;

class VimeoServiceAdapter extends AbstractServiceAdapter
{

    const THUMBNAIL_SMALL = 'thumbnail_small';
    const THUMBNAIL_MEDIUM = 'thumbnail_medium';
    const THUMBNAIL_LARGE = 'thumbnail_large';


    public function __construct($url, $pattern)
    {
        $match = array();
        preg_match($pattern, $url, $match);
        /*Gets the Video ID*/
        $videoId = $match[2];
        if (empty($videoId)) {
            $videoId = $match[1];
        }

        $this->setVideoId($videoId);

        /*Sends the video ID to the API to get the thumbnails and other infos*/
        $hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$videoId.php"));
        $data = $hash[0];


        $this->setThumbnails(array(
            self::THUMBNAIL_SMALL => $data[self::THUMBNAIL_SMALL],
            self::THUMBNAIL_MEDIUM => $data[self::THUMBNAIL_MEDIUM],
            self::THUMBNAIL_LARGE => $data[self::THUMBNAIL_LARGE],
        ));

        $this->setTitle($data['title']);
        $this->setDescription($data['description']);

        return parent::__construct($url, $pattern);
    }

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
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName()
    {
        return 'Vimeo';
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
        if (false == in_array($size, $this->getThumbNailSizes())) {
            throw new InvalidThumbnailSizeException();
        }

        return $this->thumbnails[$size];
    }

    /**
     * @param bool $autoplay
     * @return string
     */
    public function getEmbedUrl($autoplay = false)
    {
        return "http://player.vimeo.com/video/$videoId?byline=0&amp;portrait=0&amp" . ($autoplay ? '&amp&autoplay=1' : '');
    }

    /**
     * Returns all thumbnails available sizes
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


}
