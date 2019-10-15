<?php declare(strict_types=1);

namespace RicardoFiorani\Adapter;

use RicardoFiorani\Exception\NotEmbeddableException;

interface VideoAdapterInterface
{
    public function getServiceName(): string;

    public function getRawUrl(): string;

    public function hasThumbnail(): bool;

    public function getThumbNailSizes(): array;

    public function getThumbnail(string $size): string;

    public function getSmallThumbnail(bool $forceSecure = false): string;

    public function getMediumThumbnail(bool $forceSecure = false): string;

    public function getLargeThumbnail(bool $forceSecure = false): string;

    public function getLargestThumbnail(bool $forceSecure = false): string;

    public function getEmbedUrl(bool $forceAutoplay = false, bool $forceSecure = false): string;

    /**
     * @throws NotEmbeddableException
     */
    public function getEmbedCode(
        int $width,
        int $height,
        bool $forceAutoplay = false,
        bool $forceSecure = false
    ): string;

    public function isEmbeddable(): bool;
}
