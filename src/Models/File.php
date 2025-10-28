<?php

declare(strict_types=1);

namespace HamroCDN\Models;

/**
 * @phpstan-type HamroCDNFile array{
 *      url: string,
 *      size: int
 *  }
 */
final class File
{
    public function __construct(
        private string $url,
        private int $size
    ) {}

    /** @param HamroCDNFile $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['url'],
            (int) $data['size'],
        );
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    /** @return HamroCDNFile */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'size' => $this->size,
        ];
    }
}
