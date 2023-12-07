<?php

namespace App\Security;

use App\Entity\User;
use Scheb\TwoFactorBundle\Security\Authentication\Token\TwoFactorTokenInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Serializer\SerializerInterface;

class Authenticator extends AbstractLoginFormAuthenticator
{
    private const TOO_MANY_ATTEMPT_MSG = 'Too many attempts, please try later.';

    public function __construct(private SerializerInterface $serializer, private RateLimiterFactory $mainLimiter, private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse([
            'message' => 'Access denied.',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('security.login_check');
    }

    public function authenticate(Request $request): Passport
    {
        $limiter = $this->mainLimiter->create($request->getClientIp());
        if (!$limiter->consume()->isAccepted()) {
            throw new AuthenticationException(self::TOO_MANY_ATTEMPT_MSG);
        }

        $content = json_decode($request->getContent(), true);
        $password = $request->request->get('password')
            ?? $content['password'] ?? null;
        $username = $request->request->get('username')
            ?? $content['username'] ?? null;

        if (null === $password || null === $username) {
            throw new AuthenticationException('Invalid form.');
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        $twoFactorRequired = $token instanceof TwoFactorTokenInterface;
        $json = [
            'twoFactorAuthRequired' => $twoFactorRequired,
        ];
        if (!$twoFactorRequired) {
            $json['me'] = json_decode($this->serializer->serialize($currentUser, 'json', ['groups' => ['me']]));
        }

        return new JsonResponse($json);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage(),
        ], self::TOO_MANY_ATTEMPT_MSG === $exception->getMessage() ? Response::HTTP_TOO_MANY_REQUESTS : Response::HTTP_UNAUTHORIZED);
    }
}
