<?php

namespace Application\Router\Route;


class Literal implements RouteInterface
{

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $options;

    /**
     * Literal constructor.
     * @param string $path
     * @param array $options
     */
    public function __construct(string $path, array $options)
    {
        $this->path = $path;
        $this->options = $options;
    }

    /**
     * @param string $path
     * @return array|bool
     */
    public function match(string $path)
    {
        if ($this->path === $path) {
            return $this->options;
        }
        return false;
    }

    /**
     * @param array $data
     * @return string
     */
    public function assemble(array $data = [])
    {
        return $this->path;
    }
}