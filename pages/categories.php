<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 3.1.15
 * Time: 19.04
 */
if (Utils::isButtonPressed('Create') AND Utils::checkPost('catName')){
    if(Utils::saveCategory()) {
        $_SESSION['msg'] = 'Категория успешно добавлена';
        header('Location: '.BASE_URL);
        die();
    } else {
        $_SESSION['msg'] = 'Произошла ошибка при сохранении категории';
        header('Location: '.BASE_URL);
        die();
    }
} elseif (Utils::isButtonPressed('Create') AND empty($_GET)) {
    $p = new Template('formAddCategory');

} elseif (Utils::isButtonPressed('Create') AND Utils::checkGet('cat_id') AND Utils::checkPost('themeName')) {

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
} elseif (Utils::checkGet('cat_id')){
    $themes = Utils::getThemesByIdOfCategory($_GET['cat_id']);

    if (empty($themes)){
        $p = 'В данной категории пока еще нет ни одной темы. Вы можете создать свою';
        $p .= new Template('formAddTheme');
    } else {
        $p = Utils::getHtmlListOfThemes($themes);
    }
}