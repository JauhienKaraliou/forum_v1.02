<?php

require_once('inc/inc.php');

$page = new Template('page');
$buttons = new Template('LogInOrRegisterButtons');
$msg = '';
$msgButtons = '';
$pageTitle = 'IT Forum';
$p='Главный контент форума';
$footer = "&copy Powered by O&J, 2014";
$user = new User();

var_dump(BASE_URL);

if(Utils::isButtonPressed('Exit')) {
    Utils::logOut();
}
if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/home.php';
    $buttons = new Template('ExitButton');
    $msgButtons = 'Вы вошли на форум под именем: '. $user -> getUserName();
} elseif (!empty($_GET['code']) && isset($_GET['code'])){
    include 'pages/activation.php';
} elseif (isset($_SESSION['msg'])){
    $msgButtons = $_SESSION['msg'];
    $_SESSION['msg'] = NULL;
} elseif (Utils::isButtonPressed('Register')){
    include 'pages/registration.php';
    $msgButtons = "Введите персональные данные для регистрации";
} elseif (Utils::isButtonPressed('Login')){
    include 'pages/authorization.php';
    $msgButtons = "Введите свой логин и пароль";
} else {
    $msgButtons = "Вы не аторизованы, поэтому не можете оставлять комментарии.<br>Пожалуйста, авторизируйтесь или зарегистрируйтесь";
}

$page = $page -> processTemplate(array(
    'FORM' => $p,
    'MSG' => $msg,
    'FOOTER' => $footer,
    'PAGE_TITLE' => $pageTitle,
    'MSG_BUTTONS' => $msgButtons,
    'BUTTONS' => $buttons
));

echo $page;
