<?php

require_once('inc/inc.php');
/*Большой коммент :)
+перед тем как тестировать в базе сделай у себя права администратора, т.е. статус юзера поставь 1
+у пользователя когда тот находится внутри категории тоже появляется кнопка создать, которая вызывает форму для внесения новой темы
+ * Что касается статических полей в классе юзер, оставляй как есть, честно говоря даже не знаю, как это все
+ * переделывать тогда, вообщем мне кажется много чего переписывать нужно было бы,
+ * а я уже разобралась с этим и буду их использовать, вообщем не парься по этому поводу, оставляй как есть.
+ *
+ * Еще чутка базу обновила в таблицу категория добавила поле Description
+ * дамп обновила
+ *
+ * остановилась на темах, т.е. они добавляются вроде и выводятся когда кликаешь на категорию,
+ * но вот когда кликаешь на тему, почемуто корявый адрес получается
+ * типа такого http://localhost:81/forum_v1.02/index.php?cat_id=5&theme_id%20=1
+ * отчего эти знаки процентов и 20 не знаю берутся
+ *
+ * ой намутила я чего-то не знаю как разберешься :)
+ * форму для добавления сообщения сделала formAddMessage.html
+ */

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
    include 'pages/userpages.php';

    //die();
    } elseif (Utils::isButtonPressed('Users') OR Utils::checkGet('pageid')) {
        include 'pages/userpages.php';

/*
} elseif (Utils::isButtonPressed('Users')) {
    include 'pages/userpages.php';
*/
} elseif (Utils::isButtonPressed('Register')) {    //переход на страницу авторизации
    include 'pages/registration.php';

    $msgButtons = "Введите персональные данные для регистрации";
} elseif (Utils::isButtonPressed('Login')) {     //переход на страницу авторизации
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


