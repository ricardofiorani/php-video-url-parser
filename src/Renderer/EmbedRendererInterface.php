<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 30/08/2015
 * Time: 00:54.
 */
namespace RicardoFiorani\Renderer;

interface EmbedRendererInterface
{
    /**
     * @param string $embedUrl
     * @param int    $width
     * @param int    $height
     *
     * @return string
     */
    public function renderVideoEmbedCode($embedUrl, $width, $height);
}
