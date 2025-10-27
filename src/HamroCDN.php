<?php

declare(strict_types=1);

namespace HamroCDN;

use HamroCDN\Contracts\HamroCDNContract;

final class HamroCDN implements HamroCDNContract
{
    public function index(): array
    {
        return [
            [
                'nanoId' => 'abcde12345',
            ],
            [
                'nanoId' => 'fghij67890',
            ],
        ];
    }

    public function fetch(string $nanoId): array
    {
        return [
            'nanoId' => $nanoId,
        ];
    }

    public function upload(string $filePath): array
    {
        return [
            'nanoId' => 'newfile12345',
        ];
    }

    public function uploadByURL(string $url): array
    {
        return [
            'nanoId' => 'urlfile67890',
        ];
    }
}
