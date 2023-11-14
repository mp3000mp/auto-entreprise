<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Opportunity;
use App\Entity\Tender;
use App\Entity\TenderRow;
use App\Entity\TenderStatus;
use App\Enum\MeanOfPaymentEnum;
use App\Enum\OpportunityStatusEnum;
use App\Enum\TenderStatusEnum;

class TenderControllerTest extends AbstractController
{
    public function testTenderShow(): void
    {
        $this->loginUser($this->client);
        $tenders = $this->em->getRepository(Tender::class)->findAll();

        $this->client->request('GET', sprintf('/api/tenders/%d', $tenders[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('version', $jsonResponse);
    }

    public function testTenderAdd(): void
    {
        $this->loginUser($this->client);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $rawTender = [
            'version' => 1,
            'averageDailyRate' => 300,
            'opportunity' => sprintf('/api/opportunities/%d', $opportunities[0]->getId()),
        ];

        $this->client->request('POST', '/api/tenders', content: json_encode($rawTender));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('averageDailyRate', $jsonResponse);
    }

    // todo test inconsistent status with dates
    public function testTenderEdit(): void
    {
        $this->loginUser();
        $opportunities = $this->em->getRepository(Tender::class)->findAll();
        $status = $this->em->getRepository(TenderStatus::class)->findOneBy(['label' => TenderStatusEnum::SENT]);
        $rawOpportunity = [
            'version' => 2,
            'averageDailyRate' => 350,
            'sentAt' => '2023-11-10',
            'acceptedAt' => null,
            'refusedAt' => null,
            'canceledAt' => null,
            'status' => sprintf('/api/tender_statuses/%d', $status->getId()),
            'comments' => null,
            'tenderFileDocs' => null,
            'tenderFilePdf' => null,
        ];

        $this->client->request('PUT', sprintf('/api/tenders/%d', $opportunities[0]->getId()), content: json_encode($rawOpportunity));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('averageDailyRate', $jsonResponse);
    }

    public function testTenderDelete(): void
    {
        $this->loginUser();
        $tenderRows = $this->em->getRepository(TenderRow::class)->findAll();
        $emptyTender = $this->em->getRepository(Tender::class)->findOneBy(['version' => '0']);

        $this->client->request('DELETE', sprintf('/api/tenders/%d', $tenderRows[0]->getTender()->getId()));
        $this->assertResponseCode(400);
        $this->client->request('DELETE', sprintf('/api/tenders/%d', $emptyTender->getId()));
        $this->assertResponseCode(204);
    }
}
