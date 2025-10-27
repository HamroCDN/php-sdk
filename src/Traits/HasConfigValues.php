<?php

declare(strict_types=1);

namespace HamroCDN\Traits;

use RuntimeException;

trait HasConfigValues
{
    protected string $apiKey;

    protected string $baseUrl;

    /**
     * Resolve API key and base URL from multiple sources.
     *
     * @return array{string,string} [apiKey, baseUrl]
     */
    protected function resolveConfig(?string $apiKey = null, ?string $baseUrl = null): array
    {
        $defaultURL = 'https://hamrocdn.com/api';

        $this->apiKey = $this->isEmpty($apiKey)
            ?? $this->isEmpty($this->getConfigValue('hamrocdn.api_key'))
            ?? $this->isEmpty(getenv('HAMROCDN_API_KEY'));

        $this->baseUrl = rtrim(
            $this->isEmpty($baseUrl)
            ?? $this->isEmpty($this->getConfigValue('hamrocdn.api_url'))
            ?? $this->isEmpty(getenv('HAMROCDN_API_URL'))
            ?? $defaultURL,
            '/'
        );

        if (empty($this->apiKey)) {
            throw new RuntimeException('HamroCDN API key is missing.');
        }

        return [$this->apiKey, $this->baseUrl];
    }

    /**
     * @template T
     *
     * Check if the value is empty or not.
     * In return, it returns the value if not empty and false otherwise.
     *
     * @param  T  $value
     * @return T|bool
     */
    private function isEmpty(mixed $value): mixed
    {
        return empty($value) ? false : $value;
    }

    /**
     * Retrieve a configuration value if the helper exists.
     */
    private function getConfigValue(string $key): ?string
    {
        return function_exists('config') ? config($key) : null;
    }
}
