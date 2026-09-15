<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController
{
    #[Route('/api/test')]
    public function test(): JsonResponse
    {
        return new JsonResponse(123);
    }

}