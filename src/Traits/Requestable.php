<?php

declare(strict_types=1);

namespace HamroCDN\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use HamroCDN\Exceptions\HamroCDNException;
use Throwable;

/**
 * @template T
 */
trait Requestable
{
    protected Client $client;

    /**
     * @param  array<string,mixed>  $query
     * @return T
     *
     * @throws HamroCDNException
     */
    private function get(string $endpoint, array $query = []): array
    {
        try {
            $response = $this->client->get($endpoint, ['query' => $query]);

            return $this->decodeResponse($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw HamroCDNException::networkError($e);
        } catch (Throwable $e) {
            throw new HamroCDNException('Unexpected error while performing GET request.', 1000, $e);
        }
    }

    /**
     * @param  array<string,mixed>  $options
     * @return T
     *
     * @throws HamroCDNException
     */
    private function post(string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->post($endpoint, $options);

            return $this->decodeResponse($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw HamroCDNException::networkError($e);
        } catch (Throwable $e) {
            throw new HamroCDNException('Unexpected error while performing POST request.', 1000, $e);
        }
    }

    /**
     * @return T
     *
     * @throws HamroCDNException
     */
    private function decodeResponse(string $json): array
    {
        /** @var T $decoded */
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw HamroCDNException::invalidResponse('Invalid JSON returned by API.');
        }

        return $decoded;
    }
}
