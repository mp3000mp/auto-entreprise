<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tender;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tenders')]
class TenderController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'tenders.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Tender $tender): Response
    {
        if (count($tender->getWorkedTimes()) > 0) {
            return $this->jsonError('You cannot remove a tender with worked times.', Response::HTTP_BAD_REQUEST);
        }
        if (count($tender->getStatusLogs()) > 1) {
            return $this->jsonError('You cannot remove a tender with status logs.', Response::HTTP_BAD_REQUEST);
        }

        $this->em->remove($tender->getStatusLogs()->first());
        $this->em->remove($tender);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}