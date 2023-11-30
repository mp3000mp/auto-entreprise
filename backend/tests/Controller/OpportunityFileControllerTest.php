<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Opportunity;
use App\Enum\OpportunityFileTypeEnum;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OpportunityFileControllerTest extends AbstractController
{
    public function testFetchOpportunityFile(): void
    {
        self::markTestIncomplete('todo');
    }

    public function testAddOpportunityFile(): void
    {
        $this->loginUser($this->client);

        $paramBag = self::getContainer()->get(ParameterBagInterface::class);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();

        $url = sprintf('/api/opportunity_files?type=%s&opportunityId=%d', OpportunityFileTypeEnum::OTHER->value, $opportunities[0]->getId());
        $fileName = 'the_file.txt';
        $file = new UploadedFile($paramBag->get('app.fixtures_path').'/file.txt', $fileName);

        $this->client->request('POST', $url, files: ['file' => $file]);
        $this->assertResponseCode(201);

        $folder = sprintf('%s/%s/%s', $paramBag->get('app.docs_path'), date('Y'), date('m'));
        self::assertTrue(is_dir($folder));
        $files = glob($folder.'/*'.$fileName, GLOB_MARK);
        self::assertCount(1, $files);
    }

    public function testRemoveOpportunityFile(): void
    {
        self::markTestIncomplete('todo');
    }
}
