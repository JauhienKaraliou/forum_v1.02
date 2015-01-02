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

/**
 * проберяем была ли нажата кнопка выхода
 */
if(Utils::isButtonPressed('Exit')) {
    Utils::logOut();
}

/**
 * проверяем есть ли какие-нибудь данныхе в переменных окружения для авторизации
 */
if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/home.php';
    $buttons = new Template('ExitButton');
    $msgButtons = 'Вы вошли на форум под именем: '. $user -> getUserName();
} elseif (!empty($_GET['code']) && isset($_GET['code'])){
    include 'pages/activation.php';
} elseif (isset($_SESSION['msg'])){  //checkSession('msg')?
    $msgButtons = $_SESSION['msg'];
    $_SESSION['msg'] = NULL;
} elseif (Utils::isButtonPressed('Register')){    //переход на страницу авторизации
    include 'pages/registration.php';
    $msgButtons = "Введите персональные данные для регистрации";
} elseif (Utils::isButtonPressed('Login')){     //переход на страницу авторизации
    $p = new Template('formlogin');
    $p = $p->processTemplate(array('WRONG_LOGIN_MESSAGE'=>''));
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

/*проверку на активацию аккаунта внёс в метод User->checkIfValid()
/проверку на активацию аккаунта внёс в метод User->checkIfValid()
-добавил проверку активированного пользователя
-поведение при изменении данных стало логичнее
-к ИД статуса юзера теперь можно обращаться через статическое свойство класса юзер
*/
