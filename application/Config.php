<?php

namespace Application;

class Config
{
    /**
     * @var null|self
     */
    private static $instance = null;
    private $config = [];

    private function __construct()
    {
        $this->config = require APPLICATION_PATH . '/config/application.config.php';
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}