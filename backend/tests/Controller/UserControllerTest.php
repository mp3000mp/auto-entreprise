<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\TestUtilsTrait;

class UserControllerTest extends AbstractController
{
    public function testUserIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/users');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(1, $jsonResponse);
    }
}
