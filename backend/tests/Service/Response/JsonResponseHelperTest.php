<?php

declare(strict_types=1);

namespace App\Tests\Service\Response;

use App\Service\Response\JsonResponseHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseHelperTest extends TestCase
{
    public function testCreateResponseReturnsJsonWithDefaultStatus(): void
    {
        $entity = new \stdClass();
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())
            ->method('serialize')
            ->with($entity, 'json', ['groups' => ['read']])
            ->willReturn('{"id":1}');

        $helper = new JsonResponseHelper($serializer);
        $response = $helper->createResponse($entity, ['read']);

        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
        self::assertSame('application/json', $response->headers->get('content-type'));
        self::assertSame('{"id":1}', $response->getContent());
    }

    public function testCreateResponsePassesMultipleGroups(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->expects(self::once())
            ->method('serialize')
            ->with(self::anything(), 'json', ['groups' => ['read', 'detail']])
            ->willReturn('{}');

        $helper = new JsonResponseHelper($serializer);
        $helper->createResponse(new \stdClass(), ['read', 'detail']);
    }

    public function testCreateResponseRespectsCustomStatus(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $serializer->method('serialize')->willReturn('{}');

        $helper = new JsonResponseHelper($serializer);
        $response = $helper->createResponse(new \stdClass(), [], Response::HTTP_CREATED);

        self::assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
