<?php

declare(strict_types=1);

namespace HamroCDN\Traits;

use RuntimeException;

trait HasConfigValues
{
    protected ?string $apiKey;

    protected string $baseUrl;

    /**
     * Resolve API key and base URL from multiple sources.
     *
     * @return array{string, string} [apiKey, baseUrl]
     */
    protected function resolveConfig(?string $apiKey = null, ?string $baseUrl = null): array
    {
        $defaultURL = 'https://hamrocdn.com/api';

        $this->apiKey = $this->stringOrNull($apiKey)
            ?? $this->getConfigValue('hamrocdn.api_key')
            ?? $this->getEnvValue('HAMROCDN_API_KEY');

        $this->baseUrl = rtrim(
            $baseUrl
            ?? $this->getConfigValue('hamrocdn.api_url')
            ?? $this->getEnvValue('HAMROCDN_API_URL')
            ?? $defaultURL,
            '/'
        );

        if (empty($this->apiKey)) {
            throw new RuntimeException('API key is required for HamroCDN client.');
        }

        return [$this->apiKey, $this->baseUrl];
    }

    /**
     * Retrieve a configuration value if the helper exists.
     */
    private function getConfigValue(string $key): ?string
    {
        return $this->stringOrNull(
            function_exists('config') ? config($key) : null
        );
    }

    /**
     * Retrieve a environment variable if the helper exists.
     */
    private function getEnvValue(string $key): ?string
    {
        return $this->stringOrNull(
            function_exists('env') ? getenv($key) : null
        );
    }

    /**
     * Convert false|string|null into nullable string.
     */
    private function stringOrNull(string|false|null $value): ?string
    {
        return is_string($value) && $value !== '' ? $value : null;
    }
}
