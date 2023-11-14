<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class TenderStatusControllerTest extends AbstractController
{
    public function testTenderStatusIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/tender_statuses');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(5, $jsonResponse);
    }
}
