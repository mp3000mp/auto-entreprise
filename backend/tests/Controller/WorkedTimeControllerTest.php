<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Opportunity;
use App\Entity\User;
use App\Entity\WorkedTime;

class WorkedTimeControllerTest extends AbstractController
{
    public function testWorkedTimeAdd(): void
    {
        $this->loginUser($this->client);
        $opportunities = $this->em->getRepository(Opportunity::class)->findAll();
        $users = $this->em->getRepository(User::class)->findAll();
        $rawWorkedTime = [
            'date' => '2023-11-10',
            'workedDays' => 0.25,
            'user' => sprintf('/api/users/%d', $users[0]->getId()),
            'opportunity' => sprintf('/api/opportunities/%d', $opportunities[0]->getId()),
        ];

        $this->client->request('POST', '/api/worked_times', content: json_encode($rawWorkedTime));
        $this->assertResponseCode(201);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('workedDays', $jsonResponse);
    }

    public function testWorkedTimeEdit(): void
    {
        $this->loginUser();
        $workedTimes = $this->em->getRepository(WorkedTime::class)->findAll();
        $rawWorkedTime = [
            'date' => '2023-11-10',
            'workedDays' => 0.25,
        ];

        $this->client->request('PUT', sprintf('/api/worked_times/%d', $workedTimes[0]->getId()), content: json_encode($rawWorkedTime));
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertArrayHasKey('workedDays', $jsonResponse);
    }

    public function testWorkedTimeDelete(): void
    {
        $this->loginUser();
        $workedTimes = $this->em->getRepository(WorkedTime::class)->findAll();

        $this->client->request('DELETE', sprintf('/api/worked_times/%d', $workedTimes[0]->getId()));
        $this->assertResponseCode(204);
    }
}
