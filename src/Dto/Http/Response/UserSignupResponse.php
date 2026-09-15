<?php

namespace App\Dto\Http\Response;

use App\Entity\Customer\Customer;

final readonly class UserSignupResponse implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $email,
        private string $firstName,
        private string $lastName,
        private \DateTimeImmutable $createdAt,
        private array $statusType,
    ) {
    }

    public static function fromCustomer(Customer $customer): self
    {
        $id = $customer->getId();
        $email = $customer->getEmail();
        $firstName = $customer->getFirstName();
        $lastName = $customer->getLastName();
        $createdAt = $customer->getCreatedAt();
        $statusType = $customer->getStatusType();

        if ($id === null || $email === null || $firstName === null || $lastName === null || $createdAt === null || $statusType === null) {
            throw new \LogicException('Cannot create a signup response from an incomplete customer.');
        }

        return new self($id, $email, $firstName, $lastName, $createdAt, [
            'code' => $statusType->getCode()->value,
            'label' => $statusType->getLabel(),
            'description' => $statusType->getDescription(),
        ]);
    }

    /**
     * @return array{id: int, email: string, firstName: string, lastName: string, createdAt: string, statusType: array{code: string, label: ?string, description: ?string}}
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'createdAt' => $this->createdAt->setTimezone(new \DateTimeZone('UTC'))->format(\DateTimeInterface::ATOM),
            'statusType' => $this->statusType,
        ];
    }
}
