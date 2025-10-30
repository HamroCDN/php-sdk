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
 * @phpstan-import-type HamroCDNArrayData from HamroCDNContract
 * @phpstan-import-type HamroCDNObjectWithPagination from HamroCDNContract
 */
final class HamroCDN implements HamroCDNContract
{
    /**
     * @use Requestable<HamroCDNObject>
     */
    use HasConfigValues, Requestable;

    private const HEADER_UPLOAD_MEDIUM = 'php_sdk';

    public function __construct(?string $apiKey = null, ?string $baseUrl = null, ?Client $client = null)
    {
        [$this->apiKey, $this->baseUrl] = $this->resolveConfig($apiKey, $baseUrl);

        /** @var array<string, mixed>|null $paramConfig */
        $paramConfig = $client?->getConfig();

        /** @var array<string, string> $currentHeaders */
        $currentHeaders = array_key_exists(
            'headers',
            $paramConfig ?? []
        )
            ? $paramConfig['headers']
            : [
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ];

        /** @var array<string, mixed> $currentConfig */
        $currentConfig = $paramConfig ?? [
            'base_uri' => "{$this->baseUrl}/",
            'timeout' => 15,
            'verify' => true,
        ];

        $clientHeaders = array_merge(
            $currentHeaders,
            [
                'X-Upload-Medium' => self::HEADER_UPLOAD_MEDIUM,
            ]
        );
        $clientConfig = array_merge(
            $currentConfig,
            [
                'headers' => $clientHeaders,
            ]
        );

        $this->client = new Client($clientConfig);
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

    public function all(): array
    {
        /** @var HamroCDNArrayData $response */
        $response = $this->get('uploads', [
            'paginate' => false,
        ]);

        return array_map(
            /** @param HamroCDNObject $item */
            fn (array $item): Upload => Upload::fromArray($item),
            $response['data']
        );
    }
}
