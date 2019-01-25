<?php

namespace Application\Router\Route;

interface RouteInterface
{
    public function match(string $path);

    public function assemble(array $data = []);
}