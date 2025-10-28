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
    private string $url;

    private int $size;

    public function __construct(string $url, int $size)
    {
        $this->url = $url;
        $this->size = $size;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'size' => $this->size,
        ];
    }

    /** @param HamroCDNFile $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['url'] ?? '',
            (int) ($data['size'] ?? 0),
        );
    }
}
