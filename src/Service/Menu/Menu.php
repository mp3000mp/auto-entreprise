<?php

namespace App\Service\Menu;

/**
 * Class Menu.
 */
class Menu
{
    /**
     * @var MenuItem[]
     */
    private $items = [];

    /**
     * @var array
     */
    private $langs;

    /**
     * @var string
     */
    private $currentUrl;

    /**
     * @var string|null
     */
    private $brand;

    /**
     * @var int
     */
    private $iActiveItem = -1;

    /**
     * Menu constructor.
     */
    public function __construct(string $currentUrl)
    {
        $this->currentUrl = $currentUrl;
    }

    /**
     * @return MenuItem[]
     */
    public function getSubItems(): array
    {
        if ($this->iActiveItem > -1) {
            return $this->items[$this->iActiveItem]->getSubItems();
        }

        return [];
    }

    /**
     * @return $this
     */
    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getLangs(): array
    {
        return $this->langs;
    }

    /**
     * @return $this
     */
    public function setLangs(array $langs): self
    {
        $this->langs = $langs;

        return $this;
    }

    /**
     * ajoute un route.
     *
     * @return $this
     */
    public function addItem(array $options): self
    {
        $item = new MenuItem($options);
        if ($item->checkActive($this->currentUrl)) {
            $this->iActiveItem = count($this->items);
        }
        $this->items[] = $item;

        return $this;
    }

    /**
     * ajoute une sous route.
     *
     * @return Menu
     */
    public function addSubItem(array $options): self
    {
        $item = new MenuItem($options);
        $item->checkActive($this->currentUrl);
        $this->items[count($this->items) - 1]->addSubItem($item);

        return $this;
    }

    /**
     * get all routes.
     *
     * @return MenuItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
