<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 28.12.14
 * Time: 21.08
 */


$islogged=false;
if(isset($_SESSION['islogged']) and  $_SESSION['islogged']==true and $_SESSION['username']!=null) {
    $islogged = true;
    $username = $_SESSION['username'];


} elseif (isset($_COOKIE['username']) and isset($_COOKIE['password'])) {
    $username = htmlspecialchars($_COOKIE['username']);
    $password = htmlspecialchars($_COOKIE['password']);
    $islogged = $user->checkIfValid($username, $password);

    if($islogged) {
        setcookie("username",$username, time()+3600*24);
        setcookie("password",$password,time()+3600*24);
    }

} elseif(isset($_POST['username']) and isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $islogged = $user->checkIfValid($username, $password);
    if(isset($_POST['stay_logged_in']) and $islogged==true) {
        setcookie("username",$username, time()+3600*24);
        setcookie("password",$password,time()+3600*24);
    }

} else {
    include 'authorization.php';
}
if($islogged) {
    $p.= Template::getPageElement('formlogout',array(''=>''));
    $_SESSION['username']= $username;
    $_SESSION['islogged'] = true;
    $p.= Template::getPageElement('userpages',array('USER_PAGES_LINK'=>'?area=pages',
        'USER_PAGES'=>'User individual pages'));
    if(Utils::checkGet('area','pages')) {
        include 'pages/userpages.php';
    }
} else {
    $p.= Template::getPageElement('formlogin',array('WRONG_LOGIN_MESSAGE'=>'Login/password does not match'));
}

