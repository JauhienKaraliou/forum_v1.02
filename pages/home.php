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
    $buttons = new Template('ExitButton'); //перенёс кнопку сюда, потому как еслі ошібочно ввесті логін-пароль, то
    // кнопка не должна появляться
    $msgButtons = 'Вы вошли на форум под именем: '.User::$username;// $user -> getUserName();
    $_SESSION['username']= User::$username;
    $_SESSION['userID'] = User::$userID;
    $_SESSION['uStatusID'] = User::$userStatusID;
    $_SESSION['islogged'] = true;

    /**
     * processind changing user's info
     */
    if(Utils::isButtonPressed('rewrite')) {
        $formID = htmlspecialchars($_POST['id']);
        if(User::$userID == $formID) {
            $sth=DB::getInstance()->prepare('UPDATE `users` SET `name`=:name, `email`=:email,
 `about_me`=:about_me WHERE `id`=:id ');
            $arr = array('id'=>$_POST['id'],'name'=>$_POST['name'], 'email'=>$_POST['email'], 'about_me'=>$_POST['about_me']);
            $sth->execute($arr);
        }
    }
    /**
     * message if username-password does not match
     */
} else {
    $msgButtons = 'Login-password does not match!';
    header('Location: '.BASE_URL);
}


