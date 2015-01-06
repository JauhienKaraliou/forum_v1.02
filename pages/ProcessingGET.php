<?php

//Динамическое подключение нужных страниц
//Все страницы такого типа содержат переменную $p, содержащую основной контент страницы
$action = isset($_GET['action'])?$_GET['action']:'default';
if (!file_exists('pages/'.$action.'.php')){
    $action = 'default';
    include 'pages/' . $action . '.php';
} else {
    include 'pages/' . $action . '.php';
}

//Перенаправление после авторизации
if (isset($_GET['action']) == 'Login' AND isset($_POST['username'])){
    Utils::redirect(BASE_URL);
}

//Подключение либо списка пользователей либо активации аккаунта
if (Utils::checkGet('pageid')) {
    include 'pages/Userpage.php';
} elseif (!empty($_GET['code']) && isset($_GET['code'])) {
    include 'pages/Activation.php';
}

//Проверка выбрана ли категория для которой показать темы
if (Utils::checkGet('cat_id') AND !isset($_GET['action'])){

    $themes = Utils::getThemesByIdOfCategory($_GET['cat_id']);
    if (empty($themes)){
        $p = 'В данной категории пока еще нет ни одной темы. Вы можете создать свою';
    } else {
        $p = Utils::getHtmlListOfThemes($themes);
    }
    $msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';
}

//Проверка выбрана ли тема для которой показать сообщения
if (Utils::checkGet('theme_id')) {

    $messages = Utils::getMessagesByIDTheme($_GET['theme_id']);
    if (empty($messages)) {
        $p = 'no messages. be the first!';
    } else {
        $p = Utils::getHtmlListOfMessages($messages);
    }

    $p.= Utils::getHtmlFormAddMsg();

    if (Utils::checkGet('cat_id')) {  // если переход произошёл по дереву тем добавляется кнопка возврата в категорию
        $msg = '<a href ="' . BASE_URL . '?cat_id=' . $_GET['cat_id'] . '"class="btn btn-inverse">В КАТЕГОРИЮ</a>';//@todo использовать getUrl из Utils, например Utils::getUrl(array('cat_id' => $_GET['cat_id'])
    } else { // если обратились напрямую к теме добавляется кнопка возврата в главное меню
        $msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';
    }

 }