<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TwoFactorSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        return new JsonResponse([
            'me' => json_decode($this->serializer->serialize($currentUser, 'json', ['groups' => ['me']])),
        ]);
    }
}
