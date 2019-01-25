<?php

namespace Application;

use Application\Router\Route;

class ControllerAction
{

    /**
     * request method
     * @param $method
     * @return bool
     */
    protected function isMethod($method)
    {
        if ($_SERVER['REQUEST_METHOD'] === strtoupper($method)) {
            return true;
        }
        return false;
    }

    protected function redirectToRoute($routeName)
    {
        header('Location: ' . Route::getInstance()->assemble($routeName));
        exit();
    }

}