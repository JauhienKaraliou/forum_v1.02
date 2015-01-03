<?php

require_once('inc/inc.php');

/*Большой коммент :)
перед тем как тестировать в базе сделай у себя права администратора, т.е. статус юзера поставь 1
у пользователя когда тот находится внутри категории тоже появляется кнопка создать, которая вызывает форму для внесения новой темы
 * Что касается статических полей в классе юзер, оставляй как есть, честно говоря даже не знаю, как это все
 * переделывать тогда, вообщем мне кажется много чего переписывать нужно было бы,
 * а я уже разобралась с этим и буду их использовать, вообщем не парься по этому поводу, оставляй как есть.
 *
 * Еще чутка базу обновила в таблицу категория добавила поле Description
 * дамп обновила
 *
 * остановилась на темах, т.е. они добавляются вроде и выводятся когда кликаешь на категорию,
 * но вот когда кликаешь на тему, почемуто корявый адрес получается
 * типа такого http://localhost:81/forum_v1.02/index.php?cat_id=5&theme_id%20=1
 * отчего эти знаки процентов и 20 не знаю берутся
 *
 * ой намутила я чего-то не знаю как разберешься :)
 * форму для добавления сообщения сделала formAddMessage.html
 */

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

/**
 * Проверка нажатия кнопки выхода
 */
if(Utils::isButtonPressed('Exit')) {
    Utils::logOut();
}

/**
 * Проверка залогинен ли пользователь
 */
if(Utils::checkSession('islogged') OR Utils::checkCookies('username') OR Utils::checkPost('username')) {
    include 'pages/home.php';
} else {
    $msgButtons = "Вы не аторизованы, поэтому не можете оставлять комментарии.<br>Пожалуйста, авторизируйтесь или зарегистрируйтесь";
}
if (!empty($_GET['code']) && isset($_GET['code'])){
    include 'pages/activation.php';
} elseif (isset($_SESSION['msg'])){
    $msgButtons = $_SESSION['msg'];
    $_SESSION['msg'] = NULL;
} elseif (Utils::isButtonPressed('Users') AND Utils::checkGet('pageid')){
    header('Location: '.BASE_URL);
    die();
} elseif (Utils::isButtonPressed('Users') OR Utils::checkGet('pageid')){
    include 'pages/userpages.php';
}  elseif (Utils::isButtonPressed('Register')){
    include 'pages/registration.php';
    $msgButtons = "Введите персональные данные для регистрации";
} elseif (Utils::isButtonPressed('Login')){
    $p = new Template('formlogin');
    $p = $p->processTemplate(array('WRONG_LOGIN_MESSAGE'=>''));
    $msgButtons = "Введите свой логин и пароль";
} elseif (Utils::isButtonPressed('Create') AND Utils::checkPost('catName')){
    if(Utils::saveCategory()){
        $_SESSION['msg'] = 'Категория успешно добавлена';
        header('Location: '.BASE_URL);
        die();
    } else {
        $_SESSION['msg'] = 'Произошла ошибка при сохранении категории';
        header('Location: '.BASE_URL);
        die();
    }
}elseif (Utils::isButtonPressed('Create') AND empty($_GET)){
    $p = new Template('formAddCategory');
}elseif (Utils::isButtonPressed('Create') AND Utils::checkGet('cat_id') AND Utils::checkPost('themeName')){
    if(Utils::saveTheme()){
        $_SESSION['msg'] = 'Тема успешно добавлена';
        header('Location: '.$_SERVER['REQUEST_URI']);
        die();
    } else {
        $_SESSION['msg'] = 'Произошла ошибка при сохранении темы';
        header('Location: '.$_SERVER['REQUEST_URI']);
        die();
    }
} elseif (Utils::isButtonPressed('Create') AND Utils::checkGet('cat_id')){
    $p = new Template('formAddTheme');
}elseif (Utils::checkGet('cat_id')){
    $themes = Utils::getThemesByIdOfCategory($_GET['cat_id']);
    if (empty($themes)){
        $p = 'В данной категории пока еще нет ни одной темы. Вы можете создать свою';
        $p .= new Template('formAddTheme');
    } else {
        $p = Utils::getHtmlListOfThemes($themes);
    }
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
