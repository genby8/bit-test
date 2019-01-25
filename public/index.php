<?php
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL);
ini_set('display_errors', 0);
chdir(dirname(__DIR__));
define('PROJECT_ROOT', realpath(dirname(__FILE__) . '/..'));
define('PUBLIC_PATH', PROJECT_ROOT . '/public');
define('APPLICATION_PATH', realpath(PROJECT_ROOT . '/application'));
define('LOGS_PATH', realpath(PROJECT_ROOT . '/logs'));
require PROJECT_ROOT . '/vendor/autoload.php';
\Application\Core::init()->run();