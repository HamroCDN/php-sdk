<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

/**
 * @phpstan-type HamroCDNFile array{
 *     url: string,
 *     size: int
 * }
 * @phpstan-type HamroCDNUser array{
 *     name: string,
 *     email: string,
 *     role: string
 * }
 * @phpstan-type HamroCDNObject array{
 *     nanoId: string,
 *     user: HamroCDNUser,
 *     delete_at: string|null,
 *     original: HamroCDNFile
 * }
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
