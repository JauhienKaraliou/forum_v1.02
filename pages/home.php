<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 28.12.14
 * Time: 21.08
 */


//User::$isLogged=false; // обнуление переменной

if(Utils::checkSession('islogged') and  $_SESSION['islogged']==true and Utils::checkSession('username')) {
    User::$isLogged = true;
    User::$username = $_SESSION['username'];
    User::$userID = $_SESSION['userID'];
    User::$userStatusID = $_SESSION['uStatusID'];

} elseif (Utils::checkCookies('PHPSESSID') and User::$isLogged) {
    //  $sessionName = session_name();
    setcookie('PHPSESSID', session_id(), time()+3600*24);

} elseif(Utils::checkPost('username') and Utils::checkPost('password')) {
    User::$username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    User::$isLogged = User::checkIfValid(User::$username, $password);
    if(Utils::checkPost('stay_logged_in') and User::$isLogged==true) {
        //$sessionName = session_name();
        setcookie('PHPSESSID', session_id(), time()+3600*24); //put in a method
    }
}
/**
 * processing logging in
 */
if(User::$isLogged) {
    $user = new User();
    $buttons = new Template('ExitButton');
    if (User::$userStatusID == 1){
        $buttons .= new Template('CreateButton');
        $uStatus = 'Администратора';
    } elseif (User::$userStatusID == 2 AND Utils::checkGet('cat_id')){
        $buttons .= new Template('CreateButton');
        $uStatus = 'пользователя';
    } else {
        $uStatus = 'пользователя';
    }
    $msgButtons = 'Вы вошли на форум под именем: '.User::$username . "<br> в качестве ". $uStatus;
    $_SESSION['username']= User::$username;
    $_SESSION['userID'] = User::$userID;
    $_SESSION['uStatusID'] = User::$userStatusID;
    $_SESSION['islogged'] = true;

    /**
     * processind changing user's info
     */
    if(Utils::isButtonPressed('Update')) {
        $formID = htmlspecialchars($_POST['id']);
        if(User::$userID == $formID) {
            $sth=DB::getInstance()->prepare('UPDATE `users` SET `name`=:name, `about_me`=:about_me WHERE `id`=:id ');
            $arr = array('id'=> $_POST['id'],'name'=> $_POST['name'], 'about_me'=>$_POST['about_me']);
            $sth->execute($arr);
        }
        $_SESSION['msg'] = 'Вы успешно обновили данные';
        header('Location: '.BASE_URL);
        die();
    }

    /**
     * message if username-password does not match
     */
} else {
    $_SESSION['msg'] = 'Логин и пароль не совпадают! Попробуйте снова!';
    header('Location: '.BASE_URL);
    die();
}


