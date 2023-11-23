<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Opportunity;
use App\Repository\OpportunityRepository;
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

        foreach ($opportunity->getStatusLogs() as $statusLog) {
            $this->em->remove($statusLog);
        }
        $this->em->remove($opportunity);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    // todo add doc in api platform
    #[Route('/deletable', 'opportunities.deletable', methods: ['GET'])]
    public function deletable(): Response
    {
        /** @var OpportunityRepository $repo */
        $repo = $this->em->getRepository(Opportunity::class);

        return $this->json($repo->findDeletableIds());
    }
}
