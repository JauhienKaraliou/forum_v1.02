<?php

if(Utils::checkSession('islogged') and  $_SESSION['islogged']==true and Utils::checkSession('username')) {
    User::$isLogged = true;
    User::$username = $_SESSION['username'];
    User::$userID = $_SESSION['userID'];
    User::$userStatusID = $_SESSION['uStatusID'];

} elseif (Utils::checkCookies(session_name()) and User::$isLogged) {
    setcookie(session_name(), session_id(), time()+3600*24);

} elseif(Utils::checkPost('username') and Utils::checkPost('password')) {
    User::$username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    User::$isLogged = User::checkIfValid(User::$username, $password);
    if(Utils::checkPost('stay_logged_in') and User::$isLogged==true) {
        setcookie(session_name(), session_id(), time()+3600*24); //put in a method
    }
}

if(User::$isLogged) {
    $user = new User();
    $buttons = new Template('ButtonsExitAndUsers');
    if (User::$userStatusID == 1 ){
        if(!Utils::checkGet('cat_id') AND !(isset($_GET['action']) == 'Users')){
            $buttons .= new Template('ButtonCreateCategory');
            $uStatus = 'Администратора';
        } elseif (Utils::checkGet('cat_id') AND !Utils::checkGet('theme_id')){
            $url = Utils::getUrl(array('cat_id' => $_GET['cat_id'], 'action' => 'Newtheme'));
            $button = new Template('ButtonCreateTheme');
            $buttons .= $button -> processTemplate(array('URL' => $url));
            $uStatus = 'Администратора';
        } else {
            $uStatus = 'Администратора';
        }
    } elseif (User::$userStatusID == 2 AND Utils::checkGet('cat_id') AND !Utils::checkGet('theme_id')){
        $url = Utils::getUrl(array('cat_id' => $_GET['cat_id'], 'action' => 'Newtheme'));
        $button = new Template('ButtonCreateTheme');
        $buttons .= $button -> processTemplate(array('URL' => $url));
        $uStatus = 'пользователя';
    } else {
        $uStatus = 'пользователя';
    }
    $msgButtons = 'Вы вошли на форум под именем: '.User::$username . "<br> в качестве ". $uStatus;
    $_SESSION['username']= User::$username;
    $_SESSION['userID'] = User::$userID;
    $_SESSION['uStatusID'] = User::$userStatusID;
    $_SESSION['islogged'] = true;

    if(Utils::isButtonPressed('Update')) {
        $formID = (int)$_POST['id'];
        if(User::$userID == $formID) {
            $sth=DB::getInstance()->prepare('UPDATE `users` SET `name`=:name, `about_me`=:about_me WHERE `id`=:id ');
            $arr = array('id'=> $_POST['id'],'name'=> $_POST['name'], 'about_me'=>$_POST['about_me']);
            $sth->execute($arr);
        }
        $_SESSION['msg'] = 'Вы успешно обновили персональные данные';
        Utils::redirect(BASE_URL);
    }

} else {
    $_SESSION['msg'] = 'Логин и пароль не совпадают! Попробуйте снова! Или вы не подтвердили свой E-mail';
    Utils::redirect(BASE_URL);
}


