<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Opportunity;

class OpportunityControllerTest extends AbstractController
{
    public function testOpportunityIndex(): void
    {
        $this->client->request('GET', '/api/opportunities');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(4, $jsonResponse);
    }

    public function testOpportunityShow(): void
    {
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();

        $this->client->request('GET', sprintf('/api/opportunities/%d', $opportunities[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('ref', $jsonResponse);
    }
}
