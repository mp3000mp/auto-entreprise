<?php

namespace App\Tests\Service\Menu;

use App\Service\Menu\MenuItem;
use PHPUnit\Framework\TestCase;

/**
 * Class MenuItemTest.
 */
class MenuItemTest extends TestCase
{
    private function getMenuItem(): MenuItem
    {
        return new MenuItem([
            'route' => 'user.index',
            'html' => 'entity.User.label',
            'translation_args' => ['%count%' => 2],
            'role' => 'ROLE_ADMIN',
            'regex_active' => '/^\/user/',
        ]);
    }

    /**
     * @dataProvider checkActiveProvider
     */
    public function testCheckActive(string $currentUrl, bool $expected): void
    {
        $r = $this->getMenuItem()->checkActive($currentUrl);

        self::assertEquals($expected, $r);
    }

    public function checkActiveProvider(): array
    {
        return [
            'is active' => ['/user/1/edit', true],
            'is not active' => ['/tender/4', false],
        ];
    }
}
