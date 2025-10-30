<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

use HamroCDN\Models\Upload;

/**
 * @phpstan-import-type HamroCDNObject from Upload
 * @phpstan-import-type UploadWithPagination from Upload
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
     * List all of your files in HamroCDN (with pagination).
     *
     * @return UploadWithPagination
     */
    public function index(?int $per_page = 20, ?int $page = 1): array;

    /**
     * Fetch a file from HamroCDN.
     */
    public function fetch(string $nanoId): Upload;

    /**
     * Upload a file to HamroCDN.
     */
    public function upload(string $filePath): Upload;

    /**
     * Upload a file to HamroCDN by URL.
     */
    public function uploadByURL(string $url): Upload;
}
