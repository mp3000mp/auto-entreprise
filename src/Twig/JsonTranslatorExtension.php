<?php

namespace App\Twig;

use App\Service\JsonTranslator\JsonTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class JsonTranslatorExtension extends AbstractExtension
{
    /**
     * @var JsonTranslator
     */
    private $jsonTranslator;

    public function __construct(JsonTranslator $jsonTranslator)
    {
        $this->jsonTranslator = $jsonTranslator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('jsonTrans', [$this, 'jsonTrans']),
        ];
    }

    public function jsonTrans(array $json): string
    {
        return $this->jsonTranslator->trans($json);
    }
}
