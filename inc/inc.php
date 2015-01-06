<?php

error_reporting(E_ALL);
session_start();

/* Чтобы работала база и рассылка нужно изменить на свои настройки: DB_PASSWORD, DB_NAME, DB_USER, BASE_URL*/

define('TPL_DIR', 'tpl');
define('PAGE_MES', 5);
define('CLS_DIR', 'lib');
define('DB_HOST', 'ares.beget.ru');
define('DB_USER', 'zcfddd_liskorzun');
define('DB_PASSWORD', 'liskorzun123');
define('DB_NAME', 'zcfddd_liskorzun');
define('DB_DSN', 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8');
define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
//define('BASE_URL', '/' .$_SERVER['PHP_SELF']);
//define('MAIL_USER', 'admin@jauhien-guestbook.wc.lt');
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