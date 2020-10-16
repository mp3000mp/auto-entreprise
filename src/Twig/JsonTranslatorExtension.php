<?php

namespace App\Twig;

use App\Service\JsonTranslator\JsonTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class JsonTranslatorExtension extends AbstractExtension {

	private $jsonTranslator;
	
	public function __construct(JsonTranslator $jsonTranslator) {
		$this->jsonTranslator = $jsonTranslator;
	}
	
	public function getFilters() {
		return [
			new TwigFilter('jsonTrans', [$this, 'jsonTrans']),
		];
	}
	
	public function jsonTrans( $json )
	{
		return $this->jsonTranslator->trans($json);
	}

}
