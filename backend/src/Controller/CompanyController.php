<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/companies')]
class CompanyController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'companies.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Company $company): Response
    {
        if (count($company->getContacts()) > 0) {
            return $this->jsonError('You cannot remove a company with contacts.', Response::HTTP_BAD_REQUEST);
        }
        if (count($company->getOpportunities()) > 0) {
            return $this->jsonError('You cannot remove a company with opportunities.', Response::HTTP_BAD_REQUEST);
        }

        $this->em->remove($company);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    // todo add doc in api platform
    #[Route('/deletable', 'companies.deletable', methods: ['GET'])]
    public function deletable(): Response
    {
        return $this->json($this->em->getRepository(Company::class)->findDeletableIds());
    }
}
