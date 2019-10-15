<?php declare(strict_types=1);

namespace RicardoFiorani\Renderer;

class DefaultRenderer implements EmbedRendererInterface
{
    /**
     * A friend once told me that html inside strings
     * isn't a good thing, but god knows I trying
     * to make it not dependant of some html element generator
     * and I'm too lazy/unnecessary to make one of these on my own.
     * Edit: The best I could do is do an sprintf, so...
     */
    public function renderVideoEmbedCode(string $embedUrl, int $width, int $height): string
    {
        $format = <<<HTML
<iframe 
    width="%s" 
    height="%s" 
    src="%s" 
    frameborder="0" 
    webkitAllowFullScreen mozallowfullscreen allowFullScreen>
</iframe>
HTML;

        return sprintf(
            $format,
            $width,
            $height,
            addslashes($embedUrl)
        );
    }
}
