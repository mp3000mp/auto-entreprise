<?php

namespace App\Service\JsonTranslator;


use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsonTranslator
 * @package App\Service\JsonTranslator
 */
class JsonTranslator
{
	
	private $request;
	
	public function __construct(RequestStack $requestStack) {
		$this->request = $requestStack->getCurrentRequest();
	}
	
	/**
	 * @param array $json
	 *
	 * @return string
	 */
	public function trans(?array $json):string
	{
		if($json == null){
			return '';
		}
		if(isset($json[$this->request->getLocale()])){
			return $json[$this->request->getLocale()];
		}
		if(isset($json[$this->request->getDefaultLocale()])){
			return $json[$this->request->getDefaultLocale()];
		}
		return '';
	}
	
}
