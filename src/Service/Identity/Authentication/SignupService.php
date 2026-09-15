<?php

namespace App\Service\Identity\Authentication;

use App\Cache\Identity\Authority\RoleCacheService;
use App\Cache\Identity\Status\UserStatusCacheService;
use App\Dto\Http\Request\UserSignupRequest;
use App\Entity\Customer\Cart\CustomerCart;
use App\Entity\Customer\Customer;
use App\Entity\Customer\Wallet\CustomerWallet;
use App\Enum\RoleCode;
use App\Enum\UserStatusCode;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SignupService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserStatusCacheService $userStatusCacheService,
        private readonly RoleCacheService $roleCacheService,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function createCustomer(UserSignupRequest $request): Customer
    {
        if ($this->userRepository->existsByEmail($request->email)) {
            throw new \InvalidArgumentException(sprintf('User with email %s already exists.', $request->email));
        }

        $statusType = $this->userStatusCacheService->getStatusTypes()[UserStatusCode::ACTIVE->value] ?? null;
        $customerRole = $this->roleCacheService->getRoles()[RoleCode::CUSTOMER->value] ?? null;

        if ($statusType === null || $customerRole === null) {
            throw new \LogicException('Required user status or customer role is not configured.');
        }

        $customer = new Customer();
        $customer->setEmail($request->email);
        $customer->setFirstName($request->firstName);
        $customer->setLastName($request->lastName);
        $customer->setStatusType($statusType);
        $customer->setCreatedAt(new \DateTimeImmutable());
        $customer->setPasswordHash($this->passwordHasher->hashPassword($customer, $request->password));
        $customer->addRole($customerRole);

        $this->entityManager->wrapInTransaction(function () use ($customer): void {
            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            $cart = new CustomerCart();
            $cart->setCustomer($customer);
            $this->entityManager->persist($cart);

            $wallet = new CustomerWallet();
            $wallet->setCustomer($customer);
            $wallet->setBalance('0.00');
            $this->entityManager->persist($wallet);
        });

        return $customer;
    }
}
