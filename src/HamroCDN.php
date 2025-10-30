<?php

declare(strict_types=1);

namespace HamroCDN;

use GuzzleHttp\Client;
use HamroCDN\Contracts\HamroCDNContract;
use HamroCDN\Exceptions\HamroCDNException;
use HamroCDN\Models\Upload;
use HamroCDN\Traits\HasConfigValues;
use HamroCDN\Traits\Requestable;

/**
 * @phpstan-import-type HamroCDNObject from Upload
 * @phpstan-import-type HamroCDNData from HamroCDNContract
 * @phpstan-import-type HamroCDNObjectWithPagination from HamroCDNContract
 */
final class HamroCDN implements HamroCDNContract
{
    /**
     * @use Requestable<HamroCDNData>
     */
    use HasConfigValues, Requestable;

    private const HEADER_UPLOAD_MEDIUM = 'php_sdk';

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

        $currentConfig = $this->client->getConfig();
        $currentHeaders = $currentConfig['headers'] ?? [];

        $this->client = new Client(
            array_merge($currentConfig, [
                'headers' => array_merge($currentHeaders, [
                    'X-Upload-Medium' => self::HEADER_UPLOAD_MEDIUM,
                ]),
            ])
        );
    }

    /**
     * @throws HamroCDNException
     */
    public function index(?int $per_page = 20, ?int $page = 1): array
    {
        /** @var HamroCDNObjectWithPagination $response */
        $response = $this->get('uploads', [
            'per_page' => $per_page,
            'page' => $page,
        ]);

        return [
            'data' => array_map(
                /** @param HamroCDNObject $item */
                fn (array $item): Upload => Upload::fromArray($item),
                $response['data']
            ),
            'meta' => $response['meta'],
        ];
    }

    /**
     * @throws HamroCDNException
     */
    public function fetch(string $nanoId): Upload
    {
        $response = $this->get("uploads/{$nanoId}");

        return Upload::fromArray($response['data']);
    }

    /**
     * @throws HamroCDNException
     */
    public function upload(string $filePath): Upload
    {
        if (! file_exists($filePath)) {
            throw HamroCDNException::fileError($filePath);
        }

        $response = $this->post('uploads', [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => basename($filePath),
                ],
            ],
        ]);

        return Upload::fromArray($response['data']);
    }

    /**
     * @throws HamroCDNException
     */
    public function uploadByURL(string $url): Upload
    {
        $response = $this->post('upload-from-url', [
            'json' => [
                'url' => $url,
            ],
        ]);

        return Upload::fromArray($response['data']);
    }
}
