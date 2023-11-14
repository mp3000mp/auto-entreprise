<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class OpportunityStatusConrtollerTest extends AbstractController
{
    public function testOpportunityStatusIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/opportunity_statuses');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(10, $jsonResponse);
    }
}
