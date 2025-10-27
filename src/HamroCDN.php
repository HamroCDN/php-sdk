<?php

declare(strict_types=1);

namespace HamroCDN;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HamroCDN\Contracts\HamroCDNContract;
use HamroCDN\Traits\HasConfigValues;
use HamroCDN\Traits\Requestable;
use RuntimeException;

/**
 * @phpstan-import-type HamroCDNObject from HamroCDNContract
 * @phpstan-import-type HamroCDNObjectWithPagination from HamroCDNContract
 */
final class HamroCDN implements HamroCDNContract
{
    /**
     * @use Requestable<HamroCDNObject|HamroCDNObjectWithPagination>
     */
    use HasConfigValues, Requestable;

    public function __construct(?string $apiKey = null, ?string $baseUrl = null, ?Client $client = null)
    {
        [$this->apiKey, $this->baseUrl] = $this->resolveConfig($apiKey, $baseUrl);

        $this->client = $client ?? new Client([
            'base_uri' => "{$this->baseUrl}/",
            'timeout' => 15,
            'verify' => true,
            'headers' => [
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @return HamroCDNObjectWithPagination
     *
     * @throws GuzzleException
     */
    public function index(?int $per_page = 20, ?int $page = 1): array
    {
        /** @var HamroCDNObjectWithPagination */
        return $this->get('uploads', [
            'per_page' => $per_page,
            'page' => $page,
        ]);
    }

    public function fetch(string $nanoId): array
    {
        return $this->get("uploads/{$nanoId}");
    }

    public function upload(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }

        return $this->post('uploads', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);
    }

    public function uploadByURL(string $url): array
    {
        return $this->post('upload-from-url', [
            'json' => [
                'url' => $url,
            ],
        ]);
    }
}
