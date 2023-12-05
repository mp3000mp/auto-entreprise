<?php

declare(strict_types=1);

namespace App\Service\Request;

use App\Service\Request\Exception\EntityValidationException;
use App\Service\Request\Exception\JsonSchemaException;
use JsonSchema\Validator;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JsonRequestHelper
{
    private string $pathSchemas;
    private Validator $jsonValidator;

    public function __construct(ParameterBagInterface $parameterBag, private SerializerInterface $serializer, private ValidatorInterface $validator, private LoggerInterface $logger)
    {
        $this->pathSchemas = $parameterBag->get('app.schemas_path');
        $this->jsonValidator = new Validator();
    }

    /**
     * @template T
     *
     * @param ?class-string<T> $class
     * @param T|null           $entity
     *
     * @return T|mixed
     */
    public function handleRequest(string $rawData, string $schema, string $class = null, $entity = null)
    {
        // json schema
        $jsonData = json_decode($rawData);
        $jsonSchema = json_decode(file_get_contents($this->pathSchemas.'/'.$schema.'.json'));
        $this->jsonValidator->validate($jsonData, $jsonSchema);
        if (!$this->jsonValidator->isValid()) {
            $err = "JSON does not validate with the following errors:\n";

            foreach ($this->jsonValidator->getErrors() as $error) {
                $err .= sprintf("  [%s] %s\n", $error['property'], $error['message']);
            }
            $this->logger->error($err);
            // throw new JsonSchemaException(400, $err);
            throw new JsonSchemaException(400, sprintf('Invalid request content: %s', $err));
        }

        if (null === $class) {
            return $jsonData;
        }

        // entity validation
        $context = [];
        if (null !== $entity) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $entity;
        }
        $objData = $this->serializer->deserialize($rawData, $class, 'json', $context);
        $errors = $this->validator->validate($objData);
        if (count($errors)) {
            $err = "Entity does not validate. Violations:\n";
            foreach ($errors as $error) {
                $err .= sprintf("[%s=%s] %s\n", $error->getPropertyPath(), $error->getInvalidValue(), $error->getMessage());
            }
            throw new EntityValidationException(400, $err);
        }

        return $objData;
    }
}
