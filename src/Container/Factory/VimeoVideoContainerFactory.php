<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:56
 */

namespace RicardoFiorani\Container\Factory;


use RicardoFiorani\Container\VimeoVideoContainer;

class VimeoVideoContainerFactory implements CallableFactoryInterface
{
    /**
     * @param string $url
     * @param string $pattern
     * @return VimeoVideoContainer
     */
    public function __invoke($url, $pattern)
    {
        $match = array();
        $vimeoContainer = new VimeoVideoContainer($url);
        preg_match($pattern, $url, $match);
        /*Gets the Video ID*/
        $videoId = $match[2];
        if (empty($videoId)) {
            $videoId = $match[1];
        }
        $vimeoContainer->setVideoId($videoId);

        /*Sends the video ID to the API to get the thumbnails and other infos*/
        $hash = unserialize(@file_get_contents("http://vimeo.com/api/v2/video/$videoId.php"));
        $data = $hash[0];


        $vimeoContainer->setThumbnails(array(
            VimeoVideoContainer::THUMBNAIL_SMALL => $data[VimeoVideoContainer::THUMBNAIL_SMALL],
            VimeoVideoContainer::THUMBNAIL_MEDIUM => $data[VimeoVideoContainer::THUMBNAIL_MEDIUM],
            VimeoVideoContainer::THUMBNAIL_LARGE => $data[VimeoVideoContainer::THUMBNAIL_LARGE],
        ));

        $vimeoContainer->setTitle($data['title']);
        $vimeoContainer->setDescription($data['description']);

        return $vimeoContainer;
    }
}