<?php

if (isset($_GET['action']) == 'Login' AND isset($_POST['username'])){
    Utils::redirect(BASE_URL);
}

if (Utils::checkGet('pageid')) {
    include 'pages/Userpage.php';
} elseif (!empty($_GET['code']) && isset($_GET['code'])) {
    include 'pages/Activation.php';
}

if (Utils::checkGet('cat_id') AND !isset($_GET['action'])){
    $themes = Utils::getThemesByIdOfCategory($_GET['cat_id']);
    if (empty($themes)){
        $p = 'В данной категории пока еще нет ни одной темы. Вы можете создать свою';
    } else {
        $p = Utils::getHtmlListOfThemes($themes);
        $msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';
    }
}