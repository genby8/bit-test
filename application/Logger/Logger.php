<?php

namespace Application\Logger;

use Application\Config;
use Monolog\Processor\WebProcessor;

class Logger
{

    /**
     * @var null|self
     */
    private static $loggers = [];

    /**
     * @param $name
     * @return \Monolog\Logger
     */
    public static function getLogger($name): \Monolog\Logger
    {
        if (isset(self::$loggers[$name])) {
            return self::$loggers[$name];
        }
        $config = Config::getInstance()->getConfig();
        $data = $config['log'][$name];
        $writerClass = $data['writer']['name'];
        $logger = new \Monolog\Logger($name);
        $logger->pushHandler(new $writerClass($data['writer']['options']['stream']));
        $logger->pushProcessor(new WebProcessor());
        self::$loggers[$name] = $logger;
        return $logger;
    }
}