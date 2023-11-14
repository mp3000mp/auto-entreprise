<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Tender;
use App\Entity\TenderRow;

class TenderRowControllerTest extends AbstractController
{
    public function testTenderRowAdd(): void
    {
        $this->loginUser($this->client);
        $tenders = $this->em->getRepository(Tender::class)->findAll();
        $rawTenderRow = [
            'position' => 100,
            'soldDays' => 1.5,
            'title' => 'this title',
            'description' => 'this description',
            'tender' => sprintf('/api/tenders/%d', $tenders[0]->getId()),
        ];

        $this->client->request('POST', '/api/tender_rows', content: json_encode($rawTenderRow));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('soldDays', $jsonResponse);
    }

    public function testTenderRowEdit(): void
    {
        $this->loginUser();
        $tenderRows = $this->em->getRepository(TenderRow::class)->findAll();
        $rawTenderRow = [
            'position' => 100,
            'soldDays' => 1.5,
            'title' => 'this title',
            'description' => 'this description',
        ];

        $this->client->request('PUT', sprintf('/api/tender_rows/%d', $tenderRows[0]->getId()), content: json_encode($rawTenderRow));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('soldDays', $jsonResponse);
    }

    public function testTenderRowDelete(): void
    {
        $this->loginUser();
        $tenderRows = $this->em->getRepository(TenderRow::class)->findAll();

        $this->client->request('DELETE', sprintf('/api/tender_rows/%d', $tenderRows[0]->getId()));
        $this->assertResponseCode(204);
    }
}
