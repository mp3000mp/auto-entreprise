<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;

class CompanyControllerTest extends AbstractController
{
    public function testCompanyIndex(): void
    {
        $this->client->request('GET', '/api/companies');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(5, $jsonResponse);
    }

    public function testCompanyShow(): void
    {
        $companies = $this->em->getRepository(Company::class)->findAll();

        $this->client->request('GET', sprintf('/api/companies/%d', $companies[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('city', $jsonResponse);
    }
}
