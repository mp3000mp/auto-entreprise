<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class AppControllerTest extends AbstractController
{
    public function testAppIndex(): void
    {
        $this->client->request('GET', '/api/home');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(4, $jsonResponse);
    }

    public function testAppPing(): void
    {
        $this->client->request('GET', '/api/ping');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('version', $jsonResponse);
    }
}
