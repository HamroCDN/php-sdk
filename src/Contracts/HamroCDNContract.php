<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

/**
 * @phpstan-type HamroCDNFile array{
 *     url: string,
 *     size: int
 * }
 *
 * @phpstan-type HamroCDNUser bool
 *
 * @phpstan-type HamroCDNObject array{
 *     nanoId: string,
 *     user: HamroCDNUser,
 *     delete_at: string|null,
 *     original: HamroCDNFile
 * }
 */
interface HamroCDNContract
{
    /**
     * List all of your files in HamroCDN.
     *
     * @return HamroCDNObject[]
     */
    public function index(): array;

    /**
     * Fetch a file from HamroCDN.
     *
     * @return HamroCDNObject
     */
    public function fetch(string $nanoId): array;

    /**
     * Upload a file to HamroCDN.
     *
     * @return HamroCDNObject
     */
    public function upload(string $filePath): array;

    /**
     * Upload a file to HamroCDN by URL.
     *
     * @return HamroCDNObject
     */
    public function uploadByURL(string $url): array;
}
