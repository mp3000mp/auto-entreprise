<?php

namespace App\Service;

use App\Entity\FileInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class FileUploader
{
    private string $docsFolder;

    public function __construct(private ParameterBagInterface $parameterBag)
    {
        $this->docsFolder = $this->parameterBag->get('app.docs_path');
    }

    public function storeFile(UploadedFile $file, FileInterface $fileEntity): void
    {
        $currentFolder = $this->createFolder();
        $fileName = date('His').'_'.$file->getClientOriginalName();
        $movedFile = $file->move($currentFolder, $fileName);

        $fileEntity->setName($fileName);
        $fileEntity->setExtension($movedFile->getExtension());
        $fileEntity->setPath(substr($currentFolder.'/'.$fileName, strlen($this->docsFolder) + 1));
    }

    public function serveFile(string $relativePath): BinaryFileResponse
    {
        $path = $this->docsFolder.'/'.$relativePath;
        $file = new \SplFileInfo($path);
        $response = new BinaryFileResponse($file);

        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Type', mime_content_type($path));
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->getFilename(),
        ));

        return $response;
    }

    private function createFolder(): string
    {
        $path = $this->docsFolder;
        if (!is_dir($path)) {
            mkdir($path);
            if (!is_dir($path)) {
                throw new \Exception(sprintf('Could not create folder %s', $path));
            }
        }
        $path .= '/'.date('Y');
        if (!is_dir($path)) {
            mkdir($path);
            if (!is_dir($path)) {
                throw new \Exception(sprintf('Could not create folder %s', $path));
            }
        }
        $path .= '/'.date('m');
        if (!is_dir($path)) {
            mkdir($path);
            if (!is_dir($path)) {
                throw new \Exception(sprintf('Could not create folder %s', $path));
            }
        }

        return $path;
    }
}
