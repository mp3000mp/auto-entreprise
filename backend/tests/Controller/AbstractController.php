<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\TestUtilsTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends WebTestCase
{
    use TestUtilsTrait;

    /**
     * @var array<string, string>
     */
    private array $userByRole = [
        'ROLE_USER' => 'user',
    ];
    protected KernelBrowser $client;

    public function tearDown(): void
    {
        parent::tearDown();

        $this->terminateTest();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');

        $this->purgeDatabase();

        parent::setUp();
    }

    protected function assertResponseCode(int $expectedCode): void
    {
        $responseCode = $this->client->getResponse()->getStatusCode();

        if ($expectedCode === $responseCode) {
            self::assertEquals($expectedCode, $responseCode);

            return;
        }

        $responseContent = $this->client->getResponse()->getContent();
        $responseJson = json_decode($responseContent, true);
        if (null === $responseJson) {
            echo "Response: $responseContent";
            self::assertEquals($expectedCode, $responseCode);

            return;
        }

        dump($responseJson);
        self::assertEquals($expectedCode, $responseCode);
    }

    protected function loginUser(KernelBrowser $client, string $role = 'ROLE_USER'): void
    {
        $userRepository = $this->em->getRepository(User::class);
        $testUser = $userRepository->findOneBy(['username' => $this->userByRole[$role]]);
        $credentials = [
            'username' => $testUser->getUsername(),
            'password' => 'Test2000!',
        ];
        $client->request('POST', '/api/login', [], [], [], json_encode($credentials));
        $this->assertResponseCode(200);

        // $data = json_decode($client->getResponse()->getContent(), true);
        // $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }

    /**
     * @return array<string, mixed>|mixed[]
     */
    protected function getResponseJson(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }

    protected function dumpResponse(): void
    {
        echo $this->client->getResponse()->getContent();
    }
}
