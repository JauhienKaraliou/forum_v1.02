<?php

error_reporting(E_ALL);
session_start();
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

/* Чтобы работала база и рассылка нужно изменить настройки: DB_HOST, DB_PASSWORD, DB_NAME, DB_USER, MAIL_USER, MAIL_PASSWORD*/

define('TPL_DIR', 'tpl');
define('CLS_DIR', 'lib');

//localhost
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '7781070');
define('DB_NAME', 'forum');

//http://liskorzun.ru
//define('DB_HOST', 'ares.beget.ru');
//define('DB_USER', 'zcfddd_liskorzun');
//define('DB_PASSWORD', 'liskorzun123');
//define('DB_NAME', 'zcfddd_liskorzun');

//http://liskorzun.besaba.com/forum/
//define('DB_HOST', 'mysql.hostinger.ru');
//define('DB_USER', 'u854433554_lis');
//define('DB_PASSWORD', '7781070');
//define('DB_NAME', 'u854433554_forum');

define('DB_DSN', 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8');
define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME']. $_SERVER['PHP_SELF']);

//for Olesya's localhost
//define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].':'. $_SERVER['SERVER_PORT'] .$_SERVER['PHP_SELF']);

//define('MAIL_USER', 'admin@liskorzun.besaba.com');
//define('MAIL_PASSWORD', '7781070');
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