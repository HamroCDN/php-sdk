<?php

declare(strict_types=1);

namespace HamroCDN\Contracts;

use HamroCDN\HamroCDN;

/**
 * @phpstan-import-type HamroCDNObject from HamroCDN
 */
interface HamroCDNContract
{
    /**
     * Upload a file to HamroCDN.
     *
     * @return HamroCDNObject
     */
    public function upload(string $filePath): array;

    /**
     * Fetch a file from HamroCDN.
     *
     * @return HamroCDNObject
     */
    public function fetch(string $id): array;

    /**
     * Delete a file from HamroCDN.
     */
    public function delete(string $id): bool;
}
