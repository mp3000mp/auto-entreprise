<?php

declare(strict_types=1);

namespace App\Security;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorJsonFormRenderer implements TwoFactorFormRendererInterface
{
    public function renderForm(Request $request, array $templateVars): Response
    {
        return new JsonResponse(
            ['message' => 'Two-factor authentication code required.'],
            Response::HTTP_UNAUTHORIZED,
        );
    }
}
