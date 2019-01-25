<?php

namespace Application\View\Helper;

use Application\Router\Route;

class Url
{
    public function __invoke($routeName)
    {
        return Route::getInstance()->assemble($routeName);
    }
}