<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

use HamroCDN\Models\Upload;

/**
 * @phpstan-import-type HamroCDNObject from Upload
 *
 * @phpstan-type HamroCDNData array{
 *     data: Upload
 * }
 * @phpstan-type HamroCDNObjectWithPagination array{
 *     data: array<Upload>,
 *     meta: array{total: int, per_page: int, page: int}
 * }
 */
interface HamroCDNContract
{
    /**
     * List all of your files in HamroCDN.
     *
     * @return HamroCDNObjectWithPagination
     */
    public function index(): array;

    /**
     * Fetch a file from HamroCDN.
     *
     * @return HamroCDNData
     */
    public function fetch(string $nanoId): array;

    /**
     * Upload a file to HamroCDN.
     */
    public function upload(string $filePath): Upload;

    /**
     * Upload a file to HamroCDN by URL.
     */
    public function uploadByURL(string $url): Upload;
}
