<?php

namespace PhpHelpers\DTOs;

use App\Models\User;

class AuthResponseDTO implements \JsonSerializable
{
    public function __construct(
        public User $user,
        public string $apiToken,
        public array $keysResponse,
    ) {}

    public function toArray(): array
    {
        return [
            ...$this->user->only($this->keysResponse),
            'api_token' => $this->apiToken
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
