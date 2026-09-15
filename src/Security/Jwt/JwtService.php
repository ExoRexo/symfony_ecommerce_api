<?php

namespace App\Security\Jwt;

use App\Dto\Http\Response\AuthTokenResponse;
use App\Entity\Identity\User;
use JsonException;

final class JwtService
{
    private const string ALGORITHM = 'HS256';
    private const string TYPE      = 'JWT';

    public function __construct(
        private readonly string $secret,
        private readonly int $expirationSeconds,
    ) {
        if (strlen($this->secret) < 32) {
            throw new \LogicException('JWT secret must contain at least 32 bytes.');
        }

        if ($this->expirationSeconds <= 0) {
            throw new \LogicException('JWT expiration must be positive.');
        }
    }

    public function generateToken(User $user): string
    {
        $userId = $user->getId();
        if ($userId === null) {
            throw new \InvalidArgumentException('Cannot generate a JWT for a user without an ID.');
        }

        $issuedAt = time();

        return $this->encode([
            'sub' => (string) $userId,
            'iat' => $issuedAt,
            'exp' => $issuedAt + $this->expirationSeconds,
        ]);
    }

    public function createTokenResponse(User $user): AuthTokenResponse
    {
        $expiresAt = new \DateTimeImmutable(sprintf('+%d seconds', $this->expirationSeconds));

        return new AuthTokenResponse(
            $this->generateToken($user),
            'Bearer',
            $expiresAt,
        );
    }

    /**
     * @throws JsonException
     */
    public function extractUserId(string $token): int
    {
        $segments = explode('.', $token);
        if (count($segments) !== 3 || in_array('', $segments, true)) {
            throw new \InvalidArgumentException('JWT format is invalid.');
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $segments;
        $header = $this->decodeJson($encodedHeader);
        $payload = $this->decodeJson($encodedPayload);

        if (($header['alg'] ?? null) !== self::ALGORITHM || ($header['typ'] ?? null) !== self::TYPE) {
            throw new \InvalidArgumentException('JWT header is invalid.');
        }

        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret, true));
        if (!hash_equals($expectedSignature, $encodedSignature)) {
            throw new \InvalidArgumentException('JWT signature is invalid.');
        }

        $userId = $payload['sub'] ?? null;
        $issuedAt = $payload['iat'] ?? null;
        $expiresAt = $payload['exp'] ?? null;
        $now = time();

        if (
            !is_int($issuedAt)
            || !is_int($expiresAt)
            || $issuedAt > $now
            || $expiresAt <= $now
            || $expiresAt <= $issuedAt
            || !is_string($userId)
            || filter_var($userId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false
        ) {
            throw new \InvalidArgumentException('JWT is expired or missing required claims.');
        }

        return (int) $userId;
    }

    /**
     * @throws JsonException
     */
    private function encode(array $payload): string
    {
        $encodedHeader = $this->base64UrlEncode(json_encode(['alg' => self::ALGORITHM, 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $encodedSignature = $this->base64UrlEncode(hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secret, true));

        return $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
    }

    /** @return array<string, mixed>
     * @throws JsonException
     */
    private function decodeJson(string $value): array
    {
        if ($value === '' || preg_match('/^[A-Za-z0-9_-]+$/D', $value) !== 1) {
            throw new \InvalidArgumentException('JWT encoding is invalid.');
        }

        $base64 = strtr($value, '-_', '+/');
        $decoded = base64_decode($base64 . str_repeat('=', (4 - strlen($base64) % 4) % 4), true);
        if ($decoded === false) {
            throw new \InvalidArgumentException('JWT encoding is invalid.');
        }

        $data = json_decode($decoded, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new \InvalidArgumentException('JWT payload is invalid.');
        }

        return $data;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
