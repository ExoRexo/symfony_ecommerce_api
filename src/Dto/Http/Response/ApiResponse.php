<?php

namespace App\Dto\Http\Response;

final readonly class ApiResponse implements \JsonSerializable
{
    /**
     * @param list<string> $errors
     */
    private function __construct(
        private mixed $payload,
        private array $errors,
        private \DateTimeImmutable $date,
    ) {
    }

    public static function success(mixed $payload = null): self
    {
        return new self($payload, [], new \DateTimeImmutable());
    }

    /**
     * @param list<string> $errors
     */
    public static function failure(array $errors): self
    {
        return new self(null, $errors, new \DateTimeImmutable());
    }

    /**
     * @return array{date: string, payload?: mixed, errors?: list<string>}
     */
    public function jsonSerialize(): array
    {
        $response = [
            'date' => $this->date->setTimezone(new \DateTimeZone('UTC'))->format(\DateTimeInterface::ATOM),
        ];

        if ($this->payload !== null) {
            $response['payload'] = $this->payload;
        }

        if ($this->errors !== []) {
            $response['errors'] = $this->errors;
        }

        return $response;
    }
}
