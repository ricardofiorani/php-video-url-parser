<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 00:55.
 */
namespace RicardoFiorani\Renderer;

class DefaultRenderer implements EmbedRendererInterface
{
    /**
     * A friend once told me that html inside strings
     * isn't a good thing, but god knows I trying
     * to make it not dependant of some html element generator
     * and too lazy to make one of these on my own.
     *
     *
     *
     * @param string $embedUrl
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public function render($embedUrl, $width, $height)
    {
        return '<iframe width="'.$width.'" height="'.$height.'" src="'.addslashes($embedUrl).'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
}
