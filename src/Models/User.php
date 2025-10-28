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
    public function __construct(
        private string $name,
        private string $email
    ) {}

    /** @param HamroCDNUser $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /** @return HamroCDNUser */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
