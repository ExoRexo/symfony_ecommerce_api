<?php

namespace App\Security\Jwt;

use App\Http\ApiResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

final class ApiAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private readonly ApiResponseFactory $responseFactory)
    {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        return $this->responseFactory->failure(['Access is denied.'], Response::HTTP_FORBIDDEN);
    }
}
