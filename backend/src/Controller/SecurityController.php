<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security.login_check', methods: ['POST'])]
    public function loginCheck(): Response
    {
        throw new \Exception('Login should be handled by Symfony internal');
    }

    #[Route(path: '/logout', name: 'security.logout', methods: ['GET'])]
    public function logout(): Response
    {
        throw new \Exception('Logout should be handled by Symfony internal');
    }
}
