<?php

namespace Application;

use Application\Router\Route;
use Application\Session\SessionWrapper;

class Core
{
    /**
     * @var array
     */
    private $config = [];

    /**
     * Core constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @return Core
     */
    public static function init(): self
    {
        return new self(Config::getInstance()->getConfig());
    }

    public function run()
    {
        $params = Route::getInstance()->route($_SERVER['REQUEST_URI']);
        SessionWrapper::start();
        $controllerName = $params['controller'];
        $actionName = $params['action'] . 'Action';
        $controller = new $controllerName();
        (new View())->render($controller->$actionName());
    }
}