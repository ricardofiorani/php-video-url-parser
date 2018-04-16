<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 14:15.
 */
namespace RicardoFiorani\Adapter;

interface VideoAdapterInterface
{
    /**
     * Returns the service name (ie: "Youtube" or "Vimeo").
     *
     * @return string
     */
    public function getServiceName();

    /**
     * Returns the input URL.
     *
     * @return string
     */
    public function getRawUrl();

    /**
     * Returns if the service has a thumbnail image.
     *
     * @return bool
     */
    public function hasThumbnail();

    /**
     * Returns all thumbnails available sizes.
     *
     * @return array
     */
    public function getThumbNailSizes();

    /**
     * @param string $size
     *
     * @return string
     */
    public function getThumbnail($size);

    /**
     * Returns the small thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     */
    public function getSmallThumbnail($forceSecure = false);

    /**
     * Returns the medium thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     */
    public function getMediumThumbnail($forceSecure = false);

    /**
     * Returns the large thumbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     */
    public function getLargeThumbnail($forceSecure = false);

    /**
     * Returns the largest thumnbnail's url.
     *
     * @param bool $forceSecure
     * @return string
     */
    public function getLargestThumbnail($forceSecure = false);

    /**
     * @param bool $forceAutoplay
     * @param bool $forceSecure
     *
     * @return string
     */
    public function getEmbedUrl($forceAutoplay = false, $forceSecure = false);

    /**
     * @param int $width
     * @param int $height
     * @param bool $forceAutoplay
     * @param bool $forceSecure
     *
     * @return string
     */
    public function getEmbedCode($width, $height, $forceAutoplay = false, $forceSecure = false);

    /**
     * @return bool
     */
    public function isEmbeddable();
}
