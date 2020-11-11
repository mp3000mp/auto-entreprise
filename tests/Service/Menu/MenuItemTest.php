<?php

namespace App\Tests\Service\Menu;

use App\Service\Menu\MenuItem;
use PHPUnit\Framework\TestCase;

/**
 * Class MenuItemTest.
 */
class MenuItemTest extends TestCase
{
    /** @var MenuItem */
    private $menuItem;

    /**
     * MenuItemTest constructor.
     *
     * @param null   $name
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->menuItem = new MenuItem([
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
    public function testCheckActive($currentUrl, $expected)
    {
        $r = $this->menuItem->checkActive($currentUrl);

        $this->assertEquals($expected, $r);
    }

    /**
     * @return array
     */
    public function checkActiveProvider()
    {
        return [
            'is active' => ['/user/1/edit', true],
            'is not active' => ['/tender/4', false],
        ];
    }
}
