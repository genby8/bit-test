<?php

namespace Application\Db;

use Application\Config;

class Db
{

    /**
     * @var null|self
     */
    private static $instance = null;
    /**
     * @var \PDO|null
     */
    private $conn = null;

    private function __construct()
    {
        $config = Config::getInstance()->getConfig();
        $host = $config['db']['host'];
        $dbname = $config['db']['dbname'];
        $username = $config['db']['user'];
        $password = $config['db']['password'];
        try {
            $this->conn = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        } catch (\PDOException $e) {
            die("Could not connect to the database $dbname :" . $e->getMessage());
        }
    }

    private function __clone()
    {
    }

    /**
     * @return Db
     */
    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return \PDO
     */
    public static function getConnect()
    {
        return self::getInstance()->conn;
    }
}