<?php

declare(strict_types=1);

namespace HamroCDN\Exceptions;

use Exception;
use Throwable;

/**
 * Base exception for all HamroCDN SDK errors.
 *
 * @api
 */
final class HamroCDNException extends Exception
{
    public function __construct(string $message = 'An error occurred with HamroCDN.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Factory for network or Guzzle-related errors.
     */
    public static function networkError(Throwable $previous): self
    {
        return new self('Network error while communicating with HamroCDN.', 1001, $previous);
    }

    /**
     * Factory for invalid or non-JSON API responses.
     */
    public static function invalidResponse(?string $response = null): self
    {
        $msg = 'Invalid JSON returned by HamroCDN API.';
        if ($response !== null) {
            $msg .= ' Response: '.mb_substr($response, 0, 200).'...';
        }

        return new self($msg, 1002);
    }

    /**
     * Factory for configuration issues like missing API key.
     */
    public static function configuration(string $detail): self
    {
        return new self("HamroCDN configuration error: {$detail}", 1003);
    }

    /**
     * Factory for invalid local file operations.
     */
    public static function fileError(string $path): self
    {
        return new self("File not found or unreadable: {$path}", 1004);
    }
}
