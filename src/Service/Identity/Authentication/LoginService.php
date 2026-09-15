<?php

namespace App\Service\Identity\Authentication;

use App\Dto\Http\Request\AuthLoginRequest;
use App\Dto\Http\Response\AuthTokenResponse;
use App\Repository\UserRepository;
use App\Security\Jwt\JwtService;
use App\Security\Principal\UserPrincipal;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

final class LoginService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JwtService $jwtService,
    ) {
    }

    public function authenticateUser(AuthLoginRequest $request): AuthTokenResponse
    {
        $user = $this->userRepository->findByEmailForUserDetails($request->email);

        if ($user === null || !$user->isEnabled() || !$this->passwordHasher->isPasswordValid($user, $request->password)) {
            throw new AuthenticationException('Authentication failed.');
        }

        return $this->jwtService->createTokenResponse($user);
    }

    public function refresh(UserPrincipal $principal): AuthTokenResponse
    {
        $user = $this->userRepository->findByIdForUserDetails($principal->getId());

        if ($user === null || !$user->isEnabled()) {
            throw new AuthenticationException('Authentication failed.');
        }

        return $this->jwtService->createTokenResponse($user);
    }
}
