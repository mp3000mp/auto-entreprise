<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\User;

class CompanyControllerTest extends AbstractController
{
    public function testCompanyIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/companies');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(5, $jsonResponse);
    }

    public function testCompanyShow(): void
    {
        $this->loginUser($this->client);
        $companies = $this->em->getRepository(Company::class)->findAll();

        $this->client->request('GET', sprintf('/api/companies/%d', $companies[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('city', $jsonResponse);
    }

    public function testCompanyAdd(): void
    {
        $this->loginUser($this->client);
        $rawCompany = [
            'name' => 'Royal',
            'street1' => 'Rue de la Reine',
            'street2' => null,
            'city' => 'Versailles',
            'postcode' => '78000',
        ];

        $this->client->request('POST', '/api/companies', content: json_encode($rawCompany));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        var_dump($jsonResponse);

        self::assertArrayHasKey('city', $jsonResponse);
    }

    public function testCompanyEdit(): void
    {
        $this->loginUser();

        $companies = $this->em->getRepository(Company::class)->findAll();
        $rawCompany = [
            'name' => 'Royal',
            'street1' => 'Rue de la Reine',
            'street2' => null,
            'city' => 'Versailles',
            'postcode' => '78000',
        ];

        $this->client->request('PUT', sprintf('/api/companies/%d', $companies[0]->getId()), content: json_encode($rawCompany));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        var_dump($jsonResponse);

        self::assertArrayHasKey('city', $jsonResponse);
    }
}
