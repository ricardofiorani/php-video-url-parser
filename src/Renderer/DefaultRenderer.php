<?php
/**
 * @author Ricardo Fiorani
 */

namespace RicardoFiorani\Renderer;

class DefaultRenderer implements EmbedRendererInterface
{
    /**
     * A friend once told me that html inside strings
     * isn't a good thing, but god knows I trying
     * to make it not dependant of some html element generator
     * and I'm too lazy/unnecessary to make one of these on my own.
     * Edit: The best I could do is do an sprintf, so...
     *
     * @param string $embedUrl
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function renderVideoEmbedCode($embedUrl, $width, $height)
    {
        return sprintf(
            '<iframe width="%s" height="%s" src="%s" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
            $width,
            $height,
            addslashes($embedUrl)
        );
    }
}
