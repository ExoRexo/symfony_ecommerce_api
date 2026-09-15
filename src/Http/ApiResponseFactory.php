<?php

namespace App\Http;

use App\Dto\Http\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponseFactory
{
    public function success(mixed $payload = null, int $status = Response::HTTP_OK): JsonResponse
    {
        return $this->json(ApiResponse::success($payload), $status);
    }

    /**
     * @param list<string> $errors
     */
    public function failure(array $errors, int $status): JsonResponse
    {
        return $this->json(ApiResponse::failure($errors), $status);
    }

    private function json(ApiResponse $response, int $status): JsonResponse
    {
        return new JsonResponse($response, $status);
    }
}
