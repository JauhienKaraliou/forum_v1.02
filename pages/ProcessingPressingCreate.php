<?php

if (Utils::isButtonPressed('Create') AND Utils::checkPost('catName')){
    if(Utils::saveCategory()) {
        $_SESSION['msg'] = 'Категория успешно добавлена';
        Utils::redirect(BASE_URL);
    } else {
        $_SESSION['msg'] = 'Произошла ошибка при сохранении категории';
        Utils::redirect(BASE_URL);
    }
}  elseif (Utils::isButtonPressed('Create') AND Utils::checkGet('cat_id') AND Utils::checkPost('themeName')){
    if(Utils::saveTheme()){
        $_SESSION['msg'] = 'Тема успешно добавлена';
        $url = Utils::getUrl(array('cat_id' => $_GET['cat_id']));
        Utils::redirect($url);
    } else {
        $_SESSION['msg'] = 'Произошла ошибка при сохранении темы';
        $url = Utils::getUrl(array('cat_id' => $_GET['cat_id']));
        Utils::redirect($url);
    }
} elseif (Utils::isButtonPressed('Create') and Utils::checkGet('theme_id') and Utils::checkPost('messagetext')) {
    if (Utils::saveMsg()) {
        $_SESSION['msg'] = 'Сообщение успешно добавлено';
        $url = Utils::getUrl(array('cat_id'=> $_GET['cat_id'],
                                   'theme_id'=>$_GET['theme_id']
        ));
        Utils::redirect($url);
    } else {
        $_SESSION['msg'] = 'Произошла ошибка сохранения сообщения';
        $url = Utils::getUrl(array('cat_id'=> $_GET['cat_id'],
            'theme_id'=>$_GET['theme_id']
        ));
        Utils::redirect($url);
    }
}