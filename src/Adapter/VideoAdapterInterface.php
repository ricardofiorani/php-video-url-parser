<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:15
 */

namespace RicardoFiorani\Adapter;


interface VideoAdapterInterface
{
    /**
     * @param string $rawUrl
     */
    public function __construct($rawUrl);

    /**
     * Returns the service name (ie: "Youtube" or "Vimeo")
     * @return string
     */
    public function getServiceName();

    /**
     * Returns the input URL
     * @return string
     */
    public function getRawUrl();

    /**
     * Returns if the service has a thumbnail image
     * @return bool
     */
    public function hasThumbnail();

    /**
     * @param string $size
     * @return string
     */
    public function getThumbnail($size);

    /**
     * @param bool $autoplay
     * @return string
     */
    public function getEmbedUrl($autoplay = false);
}