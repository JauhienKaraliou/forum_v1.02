<?php
// прости пришлось так сделать, твоя ссылочка не работал Regixter точнее у меня
//но доделала так думаю даже прикольнее будет https://yadi.sk/i/-gyHbUopdhb3F



require_once('inc/inc.php');

$page = new Template('page');
$footer = "&copy Powered by O&J, 2014";
$pageTitle = 'IT Forum';
$msg = '';
$buttons = new Template('LogInOrRegisterButtons');
$msgButtons = '';
$p='Главный контент форума';

if(Utils::isButtonPressed('Exit')) {
    Utils::logOut();
}

if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/home.php';
    $buttons = new Template('ExitButton');
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
