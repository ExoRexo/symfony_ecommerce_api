<?php

namespace App\Security\Jwt;

use App\Http\ApiResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

final readonly class ApiAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    public function __construct(private ApiResponseFactory $responseFactory)
    {
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return $this->responseFactory->failure(['Authentication failed.'], Response::HTTP_UNAUTHORIZED);
    }
}
