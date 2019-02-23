<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);

use vendor\core\Router;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

session_start();

define('WWW', __DIR__);
define('CORE', dirname(__DIR__) . '/vendor/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('LAYOUT', 'default');
define('MIN_LENGTH', 6);
define('MAX_LENGTH', 20);

require '../vendor/libs/functions.php';

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

// defaults routs
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)\/?(?P<action>[a-z-]+)?$');

Router::dispatch($query);