<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 28.12.14
 * Time: 11.31
 */

require_once('inc/inc.php');

$user = new User();
$p='';
$page = new Template('header'); //? а ожет класс сделать статическим? или подключать в него файл конфигурации где
// будут имена всех необходимых шаблонов, а в метод класса передавать только массив с подстановками, и имя
// необходимого шаблона. на выходе строка с тегами

$p.= Template::getPageElement('header',array('PAGE_TITLE'=>'IT Forum'));
if(Utils::checkPost('logout')) {
    $user->logOut();
}
echo $p;