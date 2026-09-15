<?php

namespace App\Controller;

use App\Dto\Http\Request\AuthLoginRequest;
use App\Dto\Http\Request\UserSignupRequest;
use App\Dto\Http\Response\UserSignupResponse;
use App\Http\ApiResponseFactory;
use App\Security\Principal\UserPrincipal;
use App\Service\Identity\Authentication\LoginService;
use App\Service\Identity\Authentication\SignupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[Route('/api/auth')]
final class AuthController extends AbstractController
{
    public function __construct(
        private readonly SignupService $signupService,
        private readonly LoginService $loginService,
        protected readonly ApiResponseFactory $responseFactory,
    ) {
    }

    #[Route('/signup', methods: ['POST'])]
    public function signup(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)] UserSignupRequest $request,
    ): JsonResponse
    {
        $customer = $this->signupService->createCustomer($request);

        return $this->responseFactory->success(
            UserSignupResponse::fromCustomer($customer),
            Response::HTTP_CREATED,
        );
    }

    #[Route('/login', methods: ['POST'])]
    public function login(
        #[MapRequestPayload(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)] AuthLoginRequest $request,
    ): JsonResponse
    {
        return $this->responseFactory->success($this->loginService->authenticateUser($request));
    }

    #[Route('/refresh', methods: ['POST'])]
    public function refresh(): JsonResponse
    {
        $principal = $this->getUser();
        if (!$principal instanceof UserPrincipal) {
            throw new AuthenticationException('Authentication failed.');
        }

        return $this->responseFactory->success($this->loginService->refresh($principal));
    }
}
