<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class CostTypeControllerTest extends AbstractController
{
    public function testCostTypeIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/cost_types');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(3, $jsonResponse);
    }
}
