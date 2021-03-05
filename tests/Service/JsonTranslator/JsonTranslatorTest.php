<?php

namespace App\Tests\Service\JsonTranslator;

use App\Service\JsonTranslator\JsonTranslator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class JsonTranslatorTest.
 */
class JsonTranslatorTest extends TestCase
{
    private function getRequestStack(): RequestStack
    {
        $requestStack = new RequestStack();
        $request = new Request();
        $request->setDefaultLocale('en');
        $requestStack->push($request);

        return $requestStack;
    }

    /**
     * @dataProvider transProvider
     */
    public function testTrans(string $locale, ?array $json, string $expected): void
    {
        $requestStack = $this->getRequestStack();
        $requestStack->getCurrentRequest()->setLocale($locale);
        $jsonTrans = new JsonTranslator($requestStack);
        $r = $jsonTrans->trans($json);

        self::assertEquals($expected, $r);
    }

    public function transProvider(): array
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
