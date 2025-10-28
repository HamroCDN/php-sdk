<?php

declare(strict_types=1);

namespace HamroCDN\Models;

final class Upload
{
    private string $nanoId;
    private ?User $user;
    private ?string $deleteAt;
    private File $original;

    public function __construct(string $nanoId, ?User $user, ?string $deleteAt, File $original)
    {
        $this->nanoId = $nanoId;
        $this->user = $user;
        $this->deleteAt = $deleteAt;
        $this->original = $original;
    }

    public function getNanoId(): string
    {
        return $this->nanoId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getDeleteAt(): ?string
    {
        return $this->deleteAt;
    }

    public function getOriginal(): File
    {
        return $this->original;
    }

    public function toArray(): array
    {
        return [
            'nanoId' => $this->nanoId,
            'user' => $this->user?->toArray(),
            'delete_at' => $this->deleteAt,
            'original' => $this->original->toArray(),
        ];
    }
}
