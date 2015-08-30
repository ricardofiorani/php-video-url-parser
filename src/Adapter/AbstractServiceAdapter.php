<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 29/08/2015
 * Time: 19:37
 */

namespace RicardoFiorani\Adapter;


use RicardoFiorani\Exception\NotEmbedableException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

abstract class AbstractServiceAdapter implements VideoAdapterInterface
{
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
    public $pattern;

    /**
     * @var EmbedRendererInterface
     */
    public $renderer;

    /**
     * AbstractVideoAdapter constructor.
     * @param string $url
     * @param string $pattern
     * @param EmbedRendererInterface $renderer
     */
    public function __construct($url, $pattern, EmbedRendererInterface $renderer)
    {
        $this->rawUrl = $url;
        $this->pattern = $pattern;
        $this->renderer = $renderer;
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
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return EmbedRendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param EmbedRendererInterface $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param int $width
     * @param int $height
     * @return string
     * @throws NotEmbedableException
     */
    public function getEmbedCode($width, $height)
    {
        if (false == $this->isEmbedable()) {
            throw new NotEmbedableException();
        }

        return $this->getRenderer()->render($this->getEmbedUrl(), $width, $height);
    }


}
