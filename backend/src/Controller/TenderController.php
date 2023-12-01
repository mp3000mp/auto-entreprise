<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tender;
use App\Repository\TenderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tenders')]
class TenderController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'tenders.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Tender $tender): Response
    {
        if (null !== $tender->getSentAt()) {
            return $this->jsonError('You cannot remove a sent tender.', Response::HTTP_BAD_REQUEST);
        }

        foreach ($tender->getStatusLogs() as $statusLog) {
            $this->em->remove($statusLog);
        }
        $this->em->remove($tender);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    // todo add doc in api platform
    #[Route('/deletable', 'tenders.deletable', methods: ['GET'])]
    public function deletable(): Response
    {
        /** @var TenderRepository $repo */
        $repo = $this->em->getRepository(Tender::class);

        return $this->json($repo->findDeletableIds());
    }
}
