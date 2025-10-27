<?php

declare(strict_types=1);

namespace HamroCDN\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HamroCDN\Contracts\HamroCDNContract;
use RuntimeException;

/**
 * @phpstan-import-type HamroCDNObject from HamroCDNContract
 * @template T
 */
trait Requestable
{
    protected Client $client;

    /**
     * @param  array<string,mixed>  $query
     * @return T
     *
     * @throws RuntimeException|GuzzleException
     */
    private function get(string $endpoint, array $query = []): array
    {
        $response = $this->client->get($endpoint, ['query' => $query]);

        return $this->decodeResponse($response->getBody()->getContents());
    }

    /**
     * @param  array<string,mixed>  $options
     * @return T
     *
     * @throws RuntimeException|GuzzleException
     */
    private function post(string $endpoint, array $options = []): array
    {
        $response = $this->client->post($endpoint, $options);

        return $this->decodeResponse($response->getBody()->getContents());
    }

    /**
     * @return T
     *
     * @throws RuntimeException
     */
    private function decodeResponse(string $json): array
    {
        /** @var T $decoded */
        $decoded = json_decode($json, true)['data'];
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON returned by API.');
        }

        return $decoded;
    }
}
