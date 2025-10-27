<?php

declare(strict_types=1);

namespace HamroCDN;

use GuzzleHttp\Client;
use HamroCDN\Contracts\HamroCDNContract;
use HamroCDN\Traits\HasConfigValues;
use HamroCDN\Traits\Requestable;

final class HamroCDN implements HamroCDNContract
{
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
