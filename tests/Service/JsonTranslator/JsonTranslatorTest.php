<?php

namespace App\Tests\Service\JsonTranslator;

use App\Service\JsonTranslator\JsonTranslator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsonTranslatorTest
 * @package App\Tests\Service\JsonTranslator
 */
class JsonTranslatorTest extends TestCase {
	
	/** @var RequestStack  */
	private $requestStack;
	
	
	/**
	 * JsonTranslatorTest constructor.
	 *
	 * @param null $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
		
		$requestStack = new RequestStack();
		$request = new Request();
		$request->setDefaultLocale('en');
		$requestStack->push($request);
		$this->requestStack = $requestStack;
	}
	
	/**
	 * @dataProvider transProvider
	 */
	public function testTrans($locale, $json, $expected) {

		$this->requestStack->getCurrentRequest()->setLocale($locale);
		$jsonTrans = new JsonTranslator($this->requestStack);
		$r = $jsonTrans->trans($json);
		
		$this->assertEquals($expected, $r);
		//return $r;
		
	}
	
	/**
	 * @return array
	 */
	public function transProvider()
	{
		$trad = ['fr' => 'oui', 'en' => 'yes'];
		return [
			'trad fr' => ['fr', $trad, 'oui'],
			'fallback default locale' => ['es', $trad, 'yes'],
			'locale unknown' => ['es', ['fr' => 'oui'], ''],
			'json null' => ['fr', null, ''],
		];
	}
}
