<?php

require_once('inc/inc.php');

$page = new Template('page');
$buttons = new Template('ButtonsLoginAndRegister');
$user = new User();
$msg = '';
$msgButtons = '';
$pageTitle = 'IT Forum';
$footer = "&copy Powered by O&J, 2014";

//Формирование основного контента главрной страницы
$categories = Utils::getAllCategories();
if(empty($categories)){
    $p = 'Пока нет ни одной категории';
}else{
    $p = Utils::getHtmlListOfCategories($categories);
}

//Динамическое подключение нужных страниц
//Все страницы такого типа содержат переменную $p, содержащую основной контент страницы
$action = isset($_GET['action'])?$_GET['action']:'default';
if (!file_exists('pages/'.$action.'.php')){
    $action = 'default';
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
elseif (Utils::isButtonPressed('Create')){
    include 'pages/ProcessingPressingCreate.php';
}
//Обработка суперглобального массива GET
if (isset($_GET)){
    include 'pages/ProcessingGET.php';
}
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


