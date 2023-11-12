<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Opportunity;
use App\Repository\OpportunityRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AaaController extends AbstractController
{
    #[Route('/login', 'app.login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }
}
