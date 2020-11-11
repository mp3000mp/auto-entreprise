<?php

namespace App\Form;

use App\Service\JsonTranslator\JsonTranslator;
use Symfony\Component\Form\AbstractType;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractMPType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var JsonTranslator
     */
    protected $jsonTranslator;

    public function __construct(TranslatorInterface $translator, JsonTranslator $jsonTranslator)
    {
        $this->translator = $translator;
        $this->jsonTranslator = $jsonTranslator;
    }

    protected function trans(string $id, bool $isPlurial = false, string $domain = null, string $locale = null): string
    {
        $params = ['%count%' => 1];
        if ($isPlurial) {
            $params['%count%'] = 2;
        }

        return $this->translator->trans($id, $params, $domain, $locale);
    }

    protected function jsonTrans(array $json): string
    {
        return $this->jsonTranslator->trans($json);
    }
}
