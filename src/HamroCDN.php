<?php

declare(strict_types=1);

namespace HamroCDN;

use HamroCDN\Contracts\HamroCDNContract;

/**
 * @phpstan-type HamroCDNObject array{
 *     id: string,
 *     nanoId: string,
 * }
 */
final class HamroCDN implements HamroCDNContract
{
    public function upload(string $filePath): array
    {
        return [
            'id' => 'sample_id',
            'nanoId' => 'sample_nano_id',
        ];
    }

    public function fetch(string $id): array
    {
        return [
            'id' => 'sample_id',
            'nanoId' => 'sample_nano_id',
        ];
    }

    public function delete(string $id): bool
    {
        return true;
    }
}
