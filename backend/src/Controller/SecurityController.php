<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api')]
class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security.login_check', methods: ['POST'])]
    public function loginCheck(): Response
    {
        throw new \Exception('Login should be handled by Symfony internal');
    }

    // todo add doc in api platform
    #[Route('/me', 'users.me', methods: ['GET'])]
    public function me(): Response
    {
        return $this->responseHelper->createResponse($this->getUser(), ['me']);
    }

    #[Route(path: '/logout', name: 'security.logout', methods: ['GET'])]
    public function logout(): Response
    {
        throw new \Exception('Logout should be handled by Symfony internal');
    }

    #[Route(path: '/password', name: 'security.password.edit', methods: ['PUT'])]
    public function editPassword(Request $request, #[CurrentUser] User $user, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $content = $this->requestHelper->handleRequest($request->getContent(), 'edit_password');

        if (!$passwordHasher->isPasswordValid($user, $content->currentPassword)) {
            return $this->jsonError('Le mot de passe actuel est invalide.', Response::HTTP_BAD_REQUEST);
        }

        $minChars = 8;
        if (strlen($content->newPassword) < 8) {
            return $this->jsonError("Le mot de passe doit contenir au moins $minChars caractères.", Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $content->newPassword));
        $this->em->flush();

        return $this->json(['message' => 'OK']);
    }
}
