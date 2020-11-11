<?php

namespace App\Service\JsonTranslator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsonTranslator.
 */
class JsonTranslator
{
    /**
     * @var Request|null
     */
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param array $json
     */
    public function trans(?array $json): string
    {
        if (null === $json) {
            return '';
        }
        if (isset($json[$this->request->getLocale()])) {
            return $json[$this->request->getLocale()];
        }
        if (isset($json[$this->request->getDefaultLocale()])) {
            return $json[$this->request->getDefaultLocale()];
        }

        return '';
    }
}
