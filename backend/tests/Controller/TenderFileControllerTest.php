<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Tender;
use App\Enum\TenderFileTypeEnum;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TenderFileControllerTest extends AbstractController
{
    public function testFetchTenderFile(): void
    {
        self::markTestIncomplete('todo');
    }

    public function testAddTenderFile(): void
    {
        $this->loginUser($this->client);

        $paramBag = self::getContainer()->get(ParameterBagInterface::class);
        $tenders = $this->em->getRepository(Tender::class)->findAll();

        $url = sprintf('/api/tender_files?type=%s&tenderId=%d', TenderFileTypeEnum::OTHER->value, $tenders[0]->getId());
        $fileName = 'the_file.txt';
        $file = new UploadedFile($paramBag->get('app.fixtures_path').'/file.txt', $fileName);

        $this->client->request('POST', $url, files: ['file' => $file]);
        $this->assertResponseCode(201);

        $folder = sprintf('%s/%s/%s', $paramBag->get('app.docs_path'), date('Y'), date('m'));
        self::assertTrue(is_dir($folder));
        $files = glob($folder.'/*'.$fileName, GLOB_MARK);
        self::assertCount(1, $files);
    }

    public function testRemoveTenderFile(): void
    {
        self::markTestIncomplete('todo');
    }
}
