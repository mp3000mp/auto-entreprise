<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Opportunity;

class ContactControllerTest extends AbstractController
{
    public function testContactIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/contacts');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(11, $jsonResponse);
    }

    public function testContactShow(): void
    {
        $this->loginUser($this->client);
        $contacts = $this->em->getRepository(Contact::class)->findAll();

        $this->client->request('GET', sprintf('/api/contacts/%d', $contacts[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }

    public function testContactAdd(): void
    {
        $this->loginUser($this->client);
        $companies = $this->em->getRepository(Company::class)->findAll();
        $rawContact = [
            'firstName' => 'first',
            'lastName' => 'last',
            'email' => 'first@last.fr',
            'phone' => '0130546895',
            'company' => sprintf('/api/companies/%d', $companies[0]->getId()),
        ];

        $this->client->request('POST', '/api/contacts', content: json_encode($rawContact));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }

    public function testContactEdit(): void
    {
        $this->loginUser();
        $contacts = $this->em->getRepository(Contact::class)->findAll();
        $companies = $this->em->getRepository(Company::class)->findAll();
        $rawContact = [
            'firstName' => 'first',
            'lastName' => 'last',
            'email' => 'first@last.fr',
            'phone' => '0130546895',
            'company' => sprintf('/api/companies/%d', $companies[0]->getId()),
        ];

        $this->client->request('PUT', sprintf('/api/contacts/%d', $contacts[0]->getId()), content: json_encode($rawContact));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }

    public function testContactDelete(): void
    {
        $this->loginUser();
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $emptyContact = $this->em->getRepository(Contact::class)->findOneBy(['phone' => '9999999999']);

        $this->client->request('DELETE', sprintf('/api/contacts/%d', $opportunities[0]->getContacts()->first()->getId()));
        $this->assertResponseCode(400);
        $this->client->request('DELETE', sprintf('/api/contacts/%d', $emptyContact->getId()));
        $this->assertResponseCode(204);
    }

    public function testDeletableContacts(): void
    {
        $this->loginUser();

        $this->client->request('GET', sprintf('/api/contacts/deletable'));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(5, $jsonResponse);
    }
}
