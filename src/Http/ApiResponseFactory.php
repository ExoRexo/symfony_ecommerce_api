<?php

namespace App\Http;

use App\Dto\Http\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ApiResponseFactory
{
    public function success(mixed $payload = null, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return $this->json(ApiResponse::success($payload), $status, $headers);
    }

    /**
     * @param list<string> $errors
     */
    public function failure(array $errors, int $status, array $headers = []): JsonResponse
    {
        return $this->json(ApiResponse::failure($errors), $status, $headers);
    }

    private function json(ApiResponse $response, int $status, array $headers = []): JsonResponse
    {
        return new JsonResponse($response, $status, $headers);
    }
}
