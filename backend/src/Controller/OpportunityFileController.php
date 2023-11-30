<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Opportunity;
use App\Entity\OpportunityFile;
use App\Entity\User;
use App\Enum\OpportunityFileTypeEnum;
use App\Service\FileUploader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/opportunity_files')]
class OpportunityFileController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'opportunity_files.fetch', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getOpportunityFile(OpportunityFile $opportunityFile, FileUploader $fileUploader): Response
    {
        return $fileUploader->serveFile($opportunityFile->getPath());
    }

    // todo add doc in api platform
    #[Route('', 'opportuniy_files.upload.file', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function uploadFile(Request $request, FileUploader $fileUploader, #[CurrentUser] User $user, #[MapQueryParameter] string $type, #[MapQueryParameter] int $opportunityId): Response
    {
        if (1 !== $request->files->count()) {
            return $this->jsonError('Nombre de fichiers incorrect', 400);
        }
        $opportunity = $this->em->getRepository(Opportunity::class)->find($opportunityId);
        if (null === $opportunity) {
            return $this->jsonError(sprintf('Opportunité %d introuvable', $opportunityId), 404);
        }

        $file = $request->files->getIterator()->current();
        $opportunityFile = (new OpportunityFile())
            ->setCreatedBy($user)
            ->setOpportunity($opportunity)
            ->setType(OpportunityFileTypeEnum::from($type));
        try {
            $fileUploader->storeFile($file, $opportunityFile);
        } catch (\Exception $err) {
            return $this->jsonError(sprintf('Error while storing the file: %s', $err->getMessage()), 400);
        }

        $this->em->persist($opportunityFile);
        $opportunity->addOpportunityFile($opportunityFile);
        $this->em->flush();

        return $this->responseHelper->createResponse($opportunityFile, ['opportunity_show'], Response::HTTP_CREATED);
    }

    // todo add doc in api platform
    #[Route('/{id}', 'opportunity_files.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function removeOpportunityFile(OpportunityFile $opportunityFile, ParameterBagInterface $parameterBag): Response
    {
        $this->em->remove($opportunityFile);
        $this->em->flush();
        unlink($parameterBag->get('app.docs_path').'/'.$opportunityFile->getPath());

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
