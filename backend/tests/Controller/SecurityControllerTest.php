<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class SecurityControllerTest extends AbstractController
{
    public function testLogin(): void
    {
        $this->client->request('POST', '/api/login', content: json_encode(['username' => 'user', 'password' => 'Test2000!']));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }

    public function testMe(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/me');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }

    public function testLogout(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/logout');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertEquals(['message' => 'Goodbye!'], $jsonResponse);
    }

    public function testEditPassword(): void
    {
        $this->loginUser($this->client);

        $this->client->request('PUT', '/api/password', content: json_encode(['currentPassword' => 'badPassword', 'newPassword' => 'Test3000!']));
        $this->assertResponseCode(400);

        $this->client->request('PUT', '/api/password', content: json_encode(['currentPassword' => 'Test2000!', 'newPassword' => '1234']));
        $this->assertResponseCode(400);

        $this->client->request('PUT', '/api/password', content: json_encode(['currentPassword' => 'Test2000!', 'newPassword' => 'Test3000!']));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertEquals(['message' => 'OK'], $jsonResponse);
    }
}
