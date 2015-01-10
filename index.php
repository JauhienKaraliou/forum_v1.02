<?php
//To DELETE after reading
//Пробеги по файлам .php кое-где вставляла для тебя //@todo
//Списки для пользователей сделала можно проверить http://liskorzun.ru
//Кстати на бесплатном хостинге рассылка писем уже заработала http://liskorzun.besaba.com/forum/
require_once('inc/inc.php');

$page = new Template('page');
$but = new Template('ButtonsLoginAndRegister');
$buttons = $but -> processTemplate(array(
    'URL_LOGIN' =>  Utils::getUrl(array('action' => 'Login')),
    'URL_REGISTER' => Utils::getUrl(array('action' => 'Register'))));
$msg = '';
$msgButtons = '';
$pageTitle = 'IT Форум';
$footer = "&copy Powered by O&J, 2014";

//Динамическое подключение нужных страниц
//Все страницы такого типа содержат переменную $p, содержащую основной контент страницы
$action = isset($_GET['action'])?$_GET['action']:'Default';
if (!file_exists('pages/'.$action.'.php')){
    $action = 'Default';
    include 'pages/' . $action . '.php';
} else {
    include 'pages/' . $action . '.php';
}

//Проверка, авторизован ли пользователь
if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/Home.php';
} else {
    $msgButtons = "Вы не аторизованы, поэтому не можете оставлять комментарии.<br>Пожалуйста, авторизируйтесь или зарегистрируйтесь";
}

//Нужно ли передать пользователю какое-либо сообщение
if (isset($_SESSION['msg'])) {
    $msgButtons = $_SESSION['msg'];
    $_SESSION['msg'] = NULL;
}
//Обработка нажатия кнопки Create
elseif (Utils::isButtonPressed('Create') or Utils::isButtonPressed('Delete')){
    include 'pages/ProcessingPressingCreate.php';
}
//Обработка суперглобального массива GET
include 'pages/ProcessingGET.php';

//Формирование страницы
$page = $page -> processTemplate(array(
    'CONTENT' => $p,
    'MSG' => $msg,
    'FOOTER' => $footer,
    'PAGE_TITLE' => $pageTitle,
    'MSG_BUTTONS' => $msgButtons,
    'BUTTONS' => $buttons
));

echo $page;