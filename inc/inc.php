<?php

error_reporting(E_ALL);
session_start();

define('TPL_DIR', 'tpl');
define('PAGE_MES', 5);
define('CLS_DIR', 'lib');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '7781070');
define('DB_NAME', 'forum');
define('DB_DSN', 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8');

function __autoload($classname) {
    $filename = __DIR__ . DIRECTORY_SEPARATOR . CLS_DIR . DIRECTORY_SEPARATOR. $classname .".php";
    require_once($filename);
}

