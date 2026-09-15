<?php

namespace App\Dto\Http\Response;

final readonly class AuthTokenResponse implements \JsonSerializable
{
    public function __construct(
        private string $accessToken,
        private string $tokenType,
        private \DateTimeImmutable $expiresAt,
    ) {
    }

    /**
     * @return array{accessToken: string, tokenType: string, expiresAt: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'accessToken' => $this->accessToken,
            'tokenType' => $this->tokenType,
            'expiresAt' => $this->expiresAt->setTimezone(new \DateTimeZone('UTC'))->format(\DateTimeInterface::ATOM),
        ];
    }
}
