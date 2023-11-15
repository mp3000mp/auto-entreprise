<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Opportunity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/opportunities')]
class OpportunityController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'opportunities.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Opportunity $opportunity): Response
    {
        if (count($opportunity->getTenders()) > 0) {
            return $this->jsonError('You cannot remove an opportunity with tenders.', Response::HTTP_BAD_REQUEST);
        }
        if (count($opportunity->getStatusLogs()) > 1) {
            return $this->jsonError('You cannot remove an opportunity with status logs.', Response::HTTP_BAD_REQUEST);
        }

        $this->em->remove($opportunity->getStatusLogs()->first());
        $this->em->remove($opportunity);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
