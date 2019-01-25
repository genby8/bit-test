<?php

namespace Application\Router;

use Application\Config;
use Application\Router\Route\RouteInterface;

class Route
{

    /**
     * @var null|self
     */
    private static $instance = null;

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var string
     */
    private $currentRouteName;

    private function __construct()
    {
        $config = Config::getInstance()->getConfig();
        foreach ($config['router']['routes'] as $name => $data) {
            $class = $data['type'];
            $this->addRoute($name, new $class($data['options']['route'], $data['options']['defaults']));
        }
    }

    private function __clone()
    {
    }

    /**
     * @return Route
     */
    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function assemble(string $routeName)
    {
        return $this->getRoute($routeName)->assemble([]);
    }

    public function getRoute(string $name): RouteInterface
    {
        return $this->routes[$name];
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function addRoute(string $name, RouteInterface $route)
    {
        $this->routes[$name] = $route;
    }

    public function route(string $path)
    {
        /* @var $route RouteInterface */
        foreach ($this->getRoutes() as $routeName => $route) {
            $options = $route->match($path);
            if ($options) {
                $this->currentRouteName = $routeName;
                break;
            }
        }
        if (is_null($this->currentRouteName)) {
            throw new \Error();
        }
        return $this->getRoute($this->currentRouteName)->match($path);
    }
}