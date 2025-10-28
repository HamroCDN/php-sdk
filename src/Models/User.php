<?php

declare(strict_types=1);

namespace HamroCDN\Models;

/**
 * @phpstan-type HamroCDNUser array{
 *      name: string,
 *      email: string
 *  }
 */
final class User
{
    private string $name;
    private string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    /**
     * @param HamroCDNUser $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? '',
            $data['email'] ?? '',
        );
    }
}
