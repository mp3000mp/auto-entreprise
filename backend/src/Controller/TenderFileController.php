<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tender;
use App\Entity\TenderFile;
use App\Entity\User;
use App\Enum\TenderFileTypeEnum;
use App\Service\FileUploader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/api/tender_files')]
class TenderFileController extends AbstractController
{
    // todo add doc in api platform
    #[Route('/{id}', 'tender_files.fetch', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getTenderFile(TenderFile $tenderFile, FileUploader $fileUploader): Response
    {
        return $fileUploader->serveFile($tenderFile->getPath());
    }

    // todo add doc in api platform
    #[Route('', 'tender_files.upload.file', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function uploadFile(Request $request, FileUploader $fileUploader, #[CurrentUser] User $user, #[MapQueryParameter] string $type, #[MapQueryParameter] int $tenderId): Response
    {
        if (1 !== $request->files->count()) {
            return $this->jsonError('Nombre de fichiers incorrect', 400);
        }
        $tender = $this->em->getRepository(Tender::class)->find($tenderId);
        if (null === $tender) {
            return $this->jsonError(sprintf('Devis %d introuvable', $tenderId), 404);
        }

        $tenderFile = (new TenderFile())
            ->setCreatedBy($user)
            ->setTender($tender)
            ->setType(TenderFileTypeEnum::from($type));

        $file = $request->files->getIterator()->current();
        try {
            $fileUploader->storeFile($file, $tenderFile);
        } catch (\Exception $err) {
            return $this->jsonError(sprintf('Error while storing the file: %s', $err->getMessage()), 400);
        }

        $this->em->persist($tenderFile);
        $tender->addTenderFile($tenderFile);
        $this->em->flush();

        return $this->responseHelper->createResponse($tenderFile, ['tender_show'], Response::HTTP_CREATED);
    }

    // todo add doc in api platform
    #[Route('/{id}', 'tender_files.delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function removeTenderFile(TenderFile $tenderFile, ParameterBagInterface $parameterBag): Response
    {
        $this->em->remove($tenderFile);
        $this->em->flush();
        unlink($parameterBag->get('app.docs_path').'/'.$tenderFile->getPath());

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
