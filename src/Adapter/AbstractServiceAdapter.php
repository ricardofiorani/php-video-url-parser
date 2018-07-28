<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter;

use RicardoFiorani\Adapter\Exception\InvalidUrlException;
use RicardoFiorani\Adapter\Exception\NotEmbeddableException;
use RicardoFiorani\Renderer\EmbedRendererInterface;

abstract class AbstractServiceAdapter implements VideoAdapterInterface
{
    public $rawUrl;
    public $videoId;
    public $pattern;
    public $renderer;

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

    public function setRawUrl(string $rawUrl)
    {
        $this->rawUrl = $rawUrl;
    }

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId)
    {
        $this->videoId = $videoId;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function getRenderer(): EmbedRendererInterface
    {
        return $this->renderer;
    }

    public function setRenderer(EmbedRendererInterface $renderer)
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
            throw new NotEmbeddableException(
                sprintf('The service "%s" does not provide embeddable videos', $this->getServiceName())
            );
        }

        return $this->getRenderer()->renderVideoEmbedCode(
            $this->getEmbedUrl($forceAutoplay, $forceSecure),
            $width,
            $height
        );
    }

    /**
     * @throws InvalidUrlException
     */
    public function getScheme(bool $forceSecure = false): string
    {
        if ($forceSecure) {
            return 'https';
        }

        $parsedUrlSchema = parse_url($this->rawUrl, PHP_URL_SCHEME);

        if (false !== $parsedUrlSchema) {
            return $parsedUrlSchema;
        }

        throw new InvalidUrlException(sprintf('The URL %s is not valid', $this->rawUrl));
    }

    public function isThumbnailSizeAvailable($intendedSize): bool
    {
        return in_array($intendedSize, $this->getThumbNailSizes());
    }
}
