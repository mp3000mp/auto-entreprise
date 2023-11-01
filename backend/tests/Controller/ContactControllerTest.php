<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Contact;

class ContactControllerTest extends AbstractController
{
    public function testContactShow(): void
    {
        $contacts = $this->em->getRepository(Contact::class)->findAll();

        $this->client->request('GET', sprintf('/api/contacts/%d', $contacts[0]->getId()));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('email', $jsonResponse);
    }
}
