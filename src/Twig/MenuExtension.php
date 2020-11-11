<?php

namespace App\Twig;

use App\Service\Menu\Menu;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('gen_menu', [$this, 'gen_menu'], ['needs_environment' => true]),
        ];
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function gen_menu(Environment $environment, string $currentUrl): string
    {
        // new builder
        $currentUrlParams = '';
        $i = strpos($currentUrl, '?');
        if (false !== $i) {
            $currentUrlParams = substr($currentUrl, $i + 1);
            $currentUrl = substr($currentUrl, 0, $i);
        }
        $menuBuilder = new Menu($currentUrl);
        $menuBuilder->setLangs(['fr', 'en']);

        // liens
        $menuBuilder->addItem([
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
                'route' => 'contact.index',
                'html' => 'entity.Contact.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/contact|company/',
            ])
            ->addSubItem([
                'route' => 'contact.index',
                'html' => 'entity.Contact.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/contact/',
            ])
            ->addSubItem([
                'route' => 'company.index',
                'html' => 'entity.Company.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/company/',
            ])
            ->addItem([
                'route' => 'cost.index',
                'html' => 'entity.Cost.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/cost/',
            ])
            ->addSubItem([
                'route' => 'cost.index',
                'html' => 'entity.Cost.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/cost/',
            ])
            ->addItem([
                'route' => 'user.index',
                'html' => 'entity.User.label',
                'translation_args' => ['%count%' => 2],
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/user/',
            ])
            ->addItem([
                'route' => 'reporting.index',
                'html' => 'menu.reporting',
                'role' => 'ROLE_ADMIN',
                'regex_active' => '/^\/report/',
            ])
        ;

        // render
        return $environment->render('twig/menu.html.twig', [
            'menu' => $menuBuilder,
        ]);
    }
}
