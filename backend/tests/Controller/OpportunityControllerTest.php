<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\MeanOfPayment;
use App\Entity\Opportunity;
use App\Entity\OpportunityStatus;
use App\Entity\Tender;
use App\Enum\MeanOfPaymentEnum;
use App\Enum\OpportunityStatusEnum;

class OpportunityControllerTest extends AbstractController
{
    public function testOpportunityIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/opportunities');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(5, $jsonResponse);
    }

    public function testOpportunityShow(): void
    {
        $this->loginUser($this->client);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();

        $this->client->request('GET', sprintf('/api/opportunities/%d', $opportunities[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('ref', $jsonResponse);
    }

    public function testOpportunityAdd(): void
    {
        $this->loginUser($this->client);
        $companies = $this->em->getRepository(Company::class)->findAll();
        $rawOpportunity = [
            'ref' => 'this ref',
            'description' => 'this description',
            'trackedAt' => '2023-11-10',
            'company' => sprintf('/api/companies/%d', $companies[0]->getId()),
        ];

        $this->client->request('POST', '/api/opportunities', content: json_encode($rawOpportunity));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('ref', $jsonResponse);
    }

    // todo test inconsistent status with dates
    public function testOpportunityEdit(): void
    {
        $this->loginUser();
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $status = $this->em->getRepository(OpportunityStatus::class)->findOneBy(['label' => OpportunityStatusEnum::DEVELOP_ONGOING]);
        $meanOfPayment = $this->em->getRepository(MeanOfPayment::class)->findOneBy(['label' => MeanOfPaymentEnum::TRANSFER]);
        $contacts = $this->em->getRepository(Contact::class)->findAll();
        $rawOpportunity = [
            'ref' => 'this ref',
            'description' => 'this description',
            'status' => sprintf('/api/opportunity_statuses/%d', $status->getId()),
            'meanOfPayment' => sprintf('/api/mean_of_payments/%d', $meanOfPayment->getId()),
            'trackedAt' => '2023-11-10',
            'purchasedAt' => '2023-11-11',
            'deliveredAt' => null,
            'billedAt' => null,
            'payedAt' => null,
            'canceledAt' => null,
            'forecastedDelivery' => '2023-12-01',
            'customerRef1' => 'cRef1',
            'customerRef2' => 'cRef2',
            'paymentRef' => 'payRef',
            'comments' => null,
            'billFileDocx' => null,
            'billFilePdf' => null,
            'contacts' => [
                sprintf('/api/mean_of_payments/%d', $contacts[0]->getId()),
                sprintf('/api/mean_of_payments/%d', $contacts[1]->getId()),
            ],
        ];

        $this->client->request('PUT', sprintf('/api/opportunities/%d', $opportunities[0]->getId()), content: json_encode($rawOpportunity));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('contacts', $jsonResponse);
        self::assertCount(2, $jsonResponse['contacts']);
    }

    public function testOpportunityDelete(): void
    {
        $this->loginUser();
        $tenders = $this->em->getRepository(Tender::class)->findAll();
        $emptyOpportunity = $this->em->getRepository(Opportunity::class)->findOneBy(['ref' => 'empty']);

        $this->client->request('DELETE', sprintf('/api/opportunities/%d', $tenders[0]->getOpportunity()->getId()));
        $this->assertResponseCode(400);
        $this->client->request('DELETE', sprintf('/api/opportunities/%d', $emptyOpportunity->getId()));
        $this->assertResponseCode(204);
    }
}
