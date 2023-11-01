<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class CostControllerTest extends AbstractController
{
    public function testCostIndex(): void
    {
        $this->client->request('GET', '/api/costs');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(10, $jsonResponse);
    }
}
