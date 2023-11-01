<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;

class AppControllerTest extends AbstractController
{
    public function testAppIndex(): void
    {
        $c = $this->em->getRepository(Company::class)->findAll();
        var_dump(count($c));

        $this->client->request('GET', '/api');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(4, $jsonResponse);
    }
}
