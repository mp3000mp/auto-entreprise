<?php

declare(strict_types=1);

namespace App\Tests\Controller;

class MeanOfPaymentControllerTest extends AbstractController
{
    public function testMeanOfPaymentIndex(): void
    {
        $this->loginUser($this->client);

        $this->client->request('GET', '/api/mean_of_payments');
        $this->assertResponseCode(200);
        $jsonResponse = $this->getResponseJson($this->client->getResponse());

        self::assertCount(2, $jsonResponse);
    }
}
