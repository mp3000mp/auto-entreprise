<?php

namespace App\Tests\Service\Menu;

use App\Service\Menu\Menu;
use PHPUnit\Framework\TestCase;

/**
 * Class MenuTest
 * @package App\Tests\Service\Menu
 */
class MenuTest extends TestCase {
	
	/** @var Menu */
	private $menu;
	
	
	private function initItems($currentUrl)
	{
		$this->menu = new Menu($currentUrl);
		$this->menu->addItem([
			'route' => 'app.home',
			'html' => 'menu.home',
			'icon' => 'home',
		])
		           ->addItem([
			           'route' => 'opportunity.index',
			           'html' => 'entity.Opportunity.label',
			           'translation_args' => ['%count%' => 2],
			           'role' => 'ROLE_ADMIN',
			           'regex_active' => '/^\/opportunity|tender/',
		           ])
		           ->addSubItem([
			           'route' => 'opportunity.index',
			           'html' => 'entity.Opportunity.label',
			           'translation_args' => ['%count%' => 2],
			           'role' => 'ROLE_ADMIN',
			           'regex_active' => '/^\/opportunity/',
		           ])
		           ->addSubItem([
			           'route' => 'tender.index',
			           'html' => 'entity.Tender.label',
			           'translation_args' => ['%count%' => 2],
			           'role' => 'ROLE_ADMIN',
			           'regex_active' => '/^\/tender/',
		           ])
		           ->addItem([
			           'route' => 'user.index',
			           'html' => 'entity.User.label',
			           'translation_args' => ['%count%' => 2],
			           'role' => 'ROLE_ADMIN',
			           'regex_active' => '/^\/user/',
		           ]);
	}
	
	/**
	 * @dataProvider getSubItemsProvider
	 */
	public function testGetSubItems($currentUrl, $expected) {
		$this->initItems($currentUrl);
		
		$r = $this->menu->getSubItems();
		
		$this->assertEquals($expected, count($r));
	}
	
	/**
	 * @return array
	 */
	public function getSubItemsProvider()
	{
		return [
			'there are sub items' => ['/opportunity/new', 2],
			'there are no sub items' => ['/user/1/edit', 0],
			'there is no active item' => ['/no_item', 0],
		];
	}
	
}
