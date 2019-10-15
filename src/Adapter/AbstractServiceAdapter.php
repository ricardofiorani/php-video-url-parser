<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter;

use RicardoFiorani\Exception\NotEmbeddableException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

abstract class AbstractServiceAdapter implements VideoAdapterInterface
{
    public string $rawUrl;
    public string $videoId;
    public string $pattern;

    public EmbedRendererInterface $renderer;

    public function __construct(string $url, string $pattern, EmbedRendererInterface $renderer)
    {
        $this->rawUrl = $url;
        $this->pattern = $pattern;
        $this->renderer = $renderer;
    }

    public function getRawUrl(): string
    {
        return $this->rawUrl;
    }

    public function setRawUrl($rawUrl): void
    {
        $this->rawUrl = $rawUrl;
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): void
    {
        $this->videoId = $videoId;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    public function getRenderer(): EmbedRendererInterface
    {
        return $this->renderer;
    }

    public function setRenderer(EmbedRendererInterface $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * @throws NotEmbeddableException
     */
    public function getEmbedCode(
        int $width,
        int $height,
        bool $forceAutoplay = false,
        bool $forceSecure = false
    ): string {
        if (false === $this->isEmbeddable()) {
            throw new NotEmbeddableException('This video type is not embeddable.');
        }

        return $this->getRenderer()->renderVideoEmbedCode(
            $this->getEmbedUrl($forceAutoplay, $forceSecure),
            $width,
            $height
        );
    }

    public function getScheme(bool $forceSecure = false): string
    {
        if ($forceSecure) {
            return 'https';
        }

        return parse_url($this->rawUrl, PHP_URL_SCHEME);
    }
}
