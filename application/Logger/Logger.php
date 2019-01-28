<?php

namespace Application\Logger;

use Application\Config;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Log\LoggerInterface;

class Logger
{

    /**
     * @var array
     */
    private static $loggers = [];

    /**
     * @param string $name
     * @return LoggerInterface
     */
    public static function getLogger(string $name): LoggerInterface
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
        $logger->pushProcessor(new UidProcessor());
        self::$loggers[$name] = $logger;
        return $logger;
    }
}