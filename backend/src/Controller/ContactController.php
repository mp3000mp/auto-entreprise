<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contacts')]
class ContactController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'contacts.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Contact $contact): Response
    {
        if (count($contact->getOpportunities()) > 0) {
            return $this->jsonError('You cannot remove a contact with opportunities.', Response::HTTP_BAD_REQUEST);
        }

        $this->em->remove($contact);
        $this->em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    // todo add doc in api platform
    #[Route('/deletable', 'contacts.deletable', methods: ['GET'])]
    public function deletable(): Response
    {
        return $this->json($this->em->getRepository(Contact::class)->findDeletableIds());
    }
}
