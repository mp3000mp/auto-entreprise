<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Cost;
use App\Entity\CostType;

class CostControllerTest extends AbstractController
{
    public function testCostIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/costs');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(10, $jsonResponse);
    }

    public function testCostAdd(): void
    {
        $this->loginUser($this->client);
        $costTypes = $this->em->getRepository(CostType::class)->findAll();
        $rawCost = [
            'date' => '2023-11-15',
            'amount' => 100.00,
            'description' => 'this cost',
            'type' => sprintf('/api/cost_types/%d', $costTypes[0]->getId()),
        ];

        $this->client->request('POST', '/api/costs', content: json_encode($rawCost));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('amount', $jsonResponse);
    }

    public function testCostEdit(): void
    {
        $this->loginUser();
        $costs = $this->em->getRepository(Cost::class)->findAll();
        $costTypes = $this->em->getRepository(CostType::class)->findAll();
        $rawCost = [
            'date' => '2023-11-15',
            'amount' => 100.00,
            'description' => 'this cost',
            'type' => sprintf('/api/cost_types/%d', $costTypes[0]->getId()),
        ];

        $this->client->request('PUT', sprintf('/api/costs/%d', $costs[0]->getId()), content: json_encode($rawCost));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('amount', $jsonResponse);
    }

    public function testCostDelete(): void
    {
        $this->loginUser();
        $costs = $this->em->getRepository(Cost::class)->findAll();

        $this->client->request('DELETE', sprintf('/api/costs/%d', $costs[0]->getId()));
        $this->assertResponseCode(204);
    }
}
