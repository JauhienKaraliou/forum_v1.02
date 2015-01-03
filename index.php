<?php

require_once('inc/inc.php');
/**
 * проберяем была ли нажата кнопка выхода
 */
if(Utils::isButtonPressed('Exit')) {
    Utils::logOut();
}

$page = new Template('page');
$buttons = new Template('LogInOrRegisterButtons');
$msg = '';
$msgButtons = '';
$pageTitle = 'IT Forum';
$footer = "&copy Powered by O&J, 2014";
$user = new User();
$categories = Utils::getAllCategories();
if(empty($categories)){
    $p = 'Пока нет ни одной категории';
}else{
    $p=Utils::getHtmlListOfCategories($categories);
}


include 'pages/categories.php';

/**
 * проверяем есть ли какие-нибудь данныхе в переменных окружения для авторизации
 */
if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/home.php';
} else {
$msgButtons = "Вы не аторизованы, поэтому не можете оставлять комментарии.<br>Пожалуйста, авторизируйтесь или зарегистрируйтесь";
}


if (!empty($_GET['code']) && isset($_GET['code'])) {
    include 'pages/activation.php';
} elseif (isset($_SESSION['msg'])) {  //checkSession('msg')?
    $msgButtons = $_SESSION['msg'];
    $_SESSION['msg'] = NULL;
} elseif (Utils::isButtonPressed('Users') AND Utils::checkGet('pageid')) {
    header('Location: '.BASE_URL);
    die();
} elseif (Utils::isButtonPressed('Users') OR Utils::checkGet('pageid')) {
        include 'pages/userpages.php';
} elseif (Utils::isButtonPressed('Users')) {
    include 'pages/userpages.php';
} elseif (Utils::isButtonPressed('Register')) {
    include 'pages/registration.php';
    $msgButtons = "Введите персональные данные для регистрации";
} elseif (Utils::isButtonPressed('Login')) {  
    $p = new Template('formlogin');
    $p = $p->processTemplate(array('WRONG_LOGIN_MESSAGE'=>''));
    $msgButtons = "Введите свой логин и пароль";
}


$page = $page -> processTemplate(array(
    'CONTENT' => $p,
    'MSG' => $msg,
    'FOOTER' => $footer,
    'PAGE_TITLE' => $pageTitle,
    'MSG_BUTTONS' => $msgButtons,
    'BUTTONS' => $buttons
));

echo $page;


