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

        $this->apiKey = $apiKey
            ?? $this->getConfigValue('hamrocdn.api_key')
            ?? getenv('HAMROCDN_API_KEY');

        $this->baseUrl = rtrim(
            $baseUrl
            ?? $this->getConfigValue('hamrocdn.api_url')
            ?? getenv('HAMROCDN_API_URL')
            ?? $defaultURL,
            '/'
        );

        if (empty($this->apiKey)) {
            throw new RuntimeException('HamroCDN API key is missing.');
        }

        return [$this->apiKey, $this->baseUrl];
    }

    /**
     * Retrieve a configuration value if the helper exists.
     */
    private function getConfigValue(string $key): ?string
    {
        return function_exists('config') ? config($key) : null;
    }
}
