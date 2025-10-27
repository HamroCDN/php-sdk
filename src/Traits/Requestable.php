<?php

declare(strict_types=1);

namespace HamroCDN\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

trait Requestable
{
    private Client $client;

    /**
     * @template T
     *
     * @param  array<string,mixed>  $query
     *
     * @throws RuntimeException|GuzzleException
     */
    private function get(string $endpoint, array $query = []): array
    {
        $response = $this->client->get($endpoint, ['query' => $query]);

        return $this->decodeResponse($response->getBody()->getContents());
    }

    /**
     * @template T
     *
     * @param  array<string,mixed>  $options
     *
     * @throws RuntimeException|GuzzleException
     */
    private function post(string $endpoint, array $options = []): array
    {
        $response = $this->client->post($endpoint, $options);

        return $this->decodeResponse($response->getBody()->getContents());
    }

    /**
     * @return array<string,mixed>
     *
     * @throws RuntimeException
     */
    private function decodeResponse(string $json): array
    {
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON returned by API.');
        }

        return $decoded;
    }
}
