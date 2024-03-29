<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\MeanOfPayment;
use App\Entity\Opportunity;
use App\Entity\OpportunityStatus;
use App\Entity\OpportunityStatusLog;
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
        $status = $this->em->getRepository(OpportunityStatus::class)->findOneBy(['code' => OpportunityStatusEnum::TRACKED]);
        $rawOpportunity = [
            'ref' => 'this ref',
            'description' => 'this description',
            'trackedAt' => '2023-11-10',
            'company' => sprintf('/api/companies/%d', $companies[0]->getId()),
            'status' => sprintf('/api/opportunity_statuses/%d', $status->getId()),
        ];

        $this->client->request('POST', '/api/opportunities', content: json_encode($rawOpportunity));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('ref', $jsonResponse);
        self::assertEquals('Piste', $jsonResponse['status']['label']);
        $logs = $this->em->getRepository(OpportunityStatusLog::class)->findBy(['opportunity' => $jsonResponse['id']]);
        self::assertCount(1, $logs);
    }

    // todo test inconsistent status with dates
    public function testOpportunityEdit(): void
    {
        $this->loginUser();
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $status = $this->em->getRepository(OpportunityStatus::class)->findOneBy(['code' => OpportunityStatusEnum::DEV_ONGOING]);
        $meanOfPayment = $this->em->getRepository(MeanOfPayment::class)->findOneBy(['code' => MeanOfPaymentEnum::TRANSFER]);
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
        ];

        $this->client->request('PUT', sprintf('/api/opportunities/%d', $opportunities[0]->getId()), content: json_encode($rawOpportunity));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        $logs = $this->em->getRepository(OpportunityStatusLog::class)->findBy(['opportunity' => $jsonResponse['id']]);
        self::assertCount(2, $logs);
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

    public function testDeletableOpportunities(): void
    {
        $this->loginUser();

        $this->client->request('GET', sprintf('/api/opportunities/deletable'));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(1, $jsonResponse);
    }

    public function testOpportunityDeleteContact(): void
    {
        $this->loginUser($this->client);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $contacts = $opportunities[0]->getContacts();

        var_dump(count($contacts));

        $this->client->request('DELETE', sprintf('/api/opportunities/%d/contacts/%d', $opportunities[0]->getId(), $contacts[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(0, $jsonResponse['contacts']);
    }

    public function testOpportunityAddContact(): void
    {
        $this->loginUser($this->client);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $contacts = $this->em->getRepository(Contact::class)->findAll();

        $this->client->request('POST', sprintf('/api/opportunities/%d/contacts/%d', $opportunities[0]->getId(), $contacts[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(2, $jsonResponse['contacts']);
    }
}
