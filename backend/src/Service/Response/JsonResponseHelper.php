<?php

declare(strict_types=1);

namespace App\Service\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseHelper
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @param mixed    $entity              - one entity or array of entities
     * @param string[] $serializationGroups
     */
    public function createResponse($entity, array $serializationGroups, int $status = Response::HTTP_OK): Response
    {
        return new Response(
            $this->serializer->serialize($entity, 'json', ['groups' => $serializationGroups]),
            $status,
            ['content-type' => 'application/json'],
        );
    }
}
