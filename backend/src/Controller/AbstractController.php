<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Request\JsonRequestHelper;
use App\Service\Response\JsonResponseHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(protected EntityManagerInterface $em, protected JsonRequestHelper $requestHelper, protected JsonResponseHelper $responseHelper)
    {
    }

    protected function jsonError(string $message, int $httpCode): JsonResponse
    {
        return $this->json([
            'message' => $message,
        ], $httpCode);
    }
}
