<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/api')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security.login_check', methods: ['POST'])]
    public function loginCheck(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->json([
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'lastUsername' => $authenticationUtils->getLastUsername(),
        ], Response::HTTP_UNAUTHORIZED);
    }

    #[Route(path: '/logout', name: 'security.logout', methods: ['GET'])]
    public function logout(): Response
    {
        throw new \Exception('Logout should be handled by Symfony internal');
    }
}
