<?php

namespace App\Security\Jwt;

use App\Http\ApiResponseFactory;
use App\Repository\UserRepository;
use App\Security\Principal\UserPrincipalFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class JwtAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly JwtService $jwtService,
        private readonly UserRepository $userRepository,
        private readonly UserPrincipalFactory $userPrincipalFactory,
        private readonly ApiResponseFactory $responseFactory,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return str_starts_with((string) $request->headers->get('Authorization'), 'Bearer ');
    }

    public function authenticate(Request $request): Passport
    {
        $token = substr((string) $request->headers->get('Authorization'), 7);

        try {
            $userId = $this->jwtService->extractUserId($token);
        } catch (\Throwable) {
            throw new CustomUserMessageAuthenticationException('Authentication failed.');
        }

        return new SelfValidatingPassport(new UserBadge((string) $userId, function (string $identifier) {
            $user = $this->userRepository->findByIdForUserDetails((int) $identifier);

            if ($user === null || !$user->isEnabled()) {
                throw new CustomUserMessageAuthenticationException('Authentication failed.');
            }

            return $this->userPrincipalFactory->fromUser($user);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return $this->responseFactory->failure(['Authentication failed.'], Response::HTTP_UNAUTHORIZED);
    }
}
