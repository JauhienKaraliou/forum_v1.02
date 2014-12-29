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
    $user->logOut();  //or include 'logout.php' но если в нём будет один толко вызов метода то есть ли смысл
}

if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {

    include 'pages/home.php';
} elseif (Utils::checkGet('area','registration')) {   // наверное это можно заменить на иф баттон пресед)

    include 'pages/registration.php';
} else {
    include 'pages/authorization.php';
}
$p.=Template::getPageElement('footer', array('YEAR'=>'2014'));



echo $p;