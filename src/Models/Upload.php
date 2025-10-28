<?php

declare(strict_types=1);

namespace HamroCDN\Models;

/**
 * @phpstan-import-type HamroCDNUser from User
 * @phpstan-import-type HamroCDNFile from File
 *
 * @phpstan-type HamroCDNObject array{
 *      nanoId: string,
 *      user: HamroCDNUser|null,
 *      delete_at: string|null,
 *      original: HamroCDNFile
 * }
 * @phpstan-type UploadWithPagination array{
 *      data: array<Upload>,
 *      meta: array{total: int, per_page: int, page: int}
 *  }
 */
final class Upload
{
    public function __construct(
        private string $nanoId,
        private ?User $user,
        private ?string $deleteAt,
        private File $original
    ) {}

    /** @param HamroCDNObject $data */
    public static function fromArray(array $data): self
    {
        $user = null;
        if (isset($data['user'])) {
            $user = User::fromArray($data['user']);
        }

        $original = File::fromArray($data['original']);

        return new self(
            $data['nanoId'],
            $user,
            $data['delete_at'] ?? null,
            $original
        );
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

    /** @return HamroCDNObject */
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
