<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

use HamroCDN\Models\Upload;

/**
 * @phpstan-import-type HamroCDNObject from Upload
 *
 * @phpstan-type HamroCDNData array{
 *     data: HamroCDNObject
 * }
 * @phpstan-type HamroCDNObjectWithPagination array{
 *     data: array<HamroCDNObject>,
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
     *
     * @return HamroCDNData
     */
    public function upload(string $filePath): array;

    /**
     * Upload a file to HamroCDN by URL.
     *
     * @return HamroCDNData
     */
    public function uploadByURL(string $url): array;
}
