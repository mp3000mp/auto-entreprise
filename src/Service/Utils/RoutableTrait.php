<?php

namespace App\Service\Utils;

trait RoutableTrait
{

    /**
     * @var string
     */
    protected $route;
    /**
     * @var array
     */
    protected $routeArgs = [];

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    /**
     * @param array $routeArgs
     */
    public function setRouteArgs(array $routeArgs): void
    {
        $this->routeArgs = $routeArgs;
    }
}
