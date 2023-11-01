<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Opportunity;
use App\Repository\OpportunityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AppController extends AbstractController
{
    #[Route('', 'app.index')]
    public function index(): Response
    {
        /** @var OpportunityRepository $rep */
        $rep = $this->em->getRepository(Opportunity::class);

        return $this->responseHelper->createResponse($rep->findWelcomeDashboard(), ['home']);
    }
}
