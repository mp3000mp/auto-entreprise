<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Tender;

class TenderControllerTest extends AbstractController
{
    public function testTenderShow(): void
    {
        $tenders = $this->em->getRepository(Tender::class)->findAll();

        $this->client->request('GET', sprintf('/api/tenders/%d', $tenders[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('version', $jsonResponse);
    }
}
