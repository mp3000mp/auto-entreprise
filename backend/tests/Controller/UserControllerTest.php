<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class UserControllerTest extends AbstractController
{
    public function testIndex(): void
    {
        $this->client->request('GET', '/api/user');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(1, $jsonResponse);
    }
}
