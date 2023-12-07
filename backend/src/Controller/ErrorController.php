<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * used only in test environment.
 */
class ErrorController extends AbstractController
{
    public function error(\Throwable $exception): JsonResponse
    {
        $statusCode = $exception instanceof HttpException ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;

        return new JsonResponse([
            'status' => $statusCode,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ], $statusCode);
    }
}
