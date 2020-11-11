<?php

namespace App\Service\Menu;

    use App\Service\Utils\RoutableTrait;
    use App\Service\Utils\TranslatableTrait;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    /**
     * Class MenuItem.
     */
    class MenuItem
    {
        use TranslatableTrait;
        use RoutableTrait;

        /**
         * @var string
         */
        protected $html;

        /**
         * @var bool
         */
        protected $isActive = false;

        /**
         * @var string|null
         */
        protected $role;

        /**
         * @var string|null
         */
        protected $icon;

        /**
         * @var string|null
         */
        protected $regexActive;

        /**
         * @var OptionsResolver
         */
        protected $resolver;

        /**
         * @var array
         */
        protected $options;

        /**
         * @var MenuItem[]
         */
        protected $subItems = [];

        /**
         * AbstractRoute constructor.
         */
        public function __construct(array $options = [])
        {
            $this->options = $options;
            $this->resolver = new OptionsResolver();
            $this->configureOptions();
            $this->setDefaultProp();
        }

        protected function configureOptions()
        {
            $this->resolver->setDefaults([
                'route_args' => [],
                'translation_domain' => 'messages',
                'translation_args' => [],
                'role' => null,
                'icon' => null,
                'regex_active' => null,
            ]);
            $this->resolver->setRequired(['route', 'html']);
        }

        protected function setDefaultProp()
        {
            $this->options = $this->resolver->resolve($this->options);
            $this->route = $this->options['route'];
            $this->routeArgs = $this->options['route_args'];
            $this->translationDomain = $this->options['translation_domain'];
            $this->translationArgs = $this->options['translation_args'];
            $this->html = $this->options['html'];
            $this->role = $this->options['role'];
            $this->icon = $this->options['icon'];
            $this->regexActive = $this->options['regex_active'];
        }

        /**
         * @return MenuItem[]
         */
        public function getSubItems()
        {
            return $this->subItems;
        }

        /**
         * @return $this
         */
        public function addSubItem(MenuItem $item)
        {
            $this->subItems[] = $item;

            return $this;
        }

        public function getHtml(): string
        {
            return $this->html;
        }

        public function getRole(): ?string
        {
            return $this->role;
        }

        public function getIcon(): ?string
        {
            return $this->icon;
        }

        /**
         * @return bool
         */
        public function checkActive(string $currentUrl)
        {
            if (null != $this->regexActive) {
                $this->isActive = (preg_match($this->regexActive, $currentUrl));
            } else {
                $this->isActive = false;
            }

            return $this->isActive;
        }

        public function isActive(): bool
        {
            return $this->isActive;
        }
    }
