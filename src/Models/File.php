<?php

declare(strict_types=1);

namespace HamroCDN\Models;

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
}
