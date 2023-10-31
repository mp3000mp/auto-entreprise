<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class AppControllerTest extends AbstractController
{
    public function testIndex(): void
    {
        $this->client->request('GET', '/api');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('version', $jsonResponse);
    }
}
