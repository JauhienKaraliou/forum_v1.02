<?php

error_reporting(E_ALL);
session_start();

/* Чтобы работала база и рассылка нужно изменить на свои настройки: DB_PASSWORD, DB_NAME, DB_USER, BASE_URL*/

define('TPL_DIR', 'tpl');
define('PAGE_MES', 5);
define('CLS_DIR', 'lib');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '1276547');
define('DB_NAME', 'forum');
define('DB_DSN', 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8');
define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'. $_SERVER['SERVER_PORT'] .$_SERVER['PHP_SELF']);
define('MAIL_USER', 'sendmailphp@tut.by');
define('MAIL_PASSWORD', 'OlLis7781070');


include_once('inc/lib/PHPMailer/PHPMailerAutoload.php');

function my__autoload($classname) {
    $filename = __DIR__ . DIRECTORY_SEPARATOR . CLS_DIR . DIRECTORY_SEPARATOR. $classname .".php";
    if(file_exists($filename)){
        require_once($filename);
        return true;
    }
    return false;
}
spl_autoload_register('my__autoload');