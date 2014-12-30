<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 30.12.14
 * Time: 1.43
 */

if (Utils::isButtonPressed('userpages')) {
    $userList = User::getAllUsers();
    $p .= '<ul>';
    foreach ($userList as $a) {
        $usrlnk = new Template('userlink');
        $p .= $usrlnk->processTemplate(array('USERNAME' => $a['name'] . '\'s page',
            'PAGE_ID' => '<a href="?pageid=' . $a['id'] . '"'));
    }
    $p .= '</ul>';
} elseif (Utils::checkGet('pageid')) {
    $id= htmlspecialchars($_GET['pageid']);
    $sth = DB::getInstance()->prepare('SELECT `id`,`name`,`email`,`about_me` FROM `users` WHERE `id`= :id');
    $sth->execute(array('id'=>$id));
    $userInfo = $sth->fetch();
    if($userInfo==true) {
        if($userInfo['name']==$_SESSION['username']) {
            $usrpage = new Template('ownedUserPage');
            $p.=$usrpage->processTemplate($userInfo);
        } else {
            $usrpage = new Template('userpage');
           $p.=$usrpage->processTemplate($userInfo);
        }
    } else {
        $userList = User::getAllUsers();
        $p .= '<ul>';
        foreach ($userList as $a) {
            $usrlnk = new Template('userlink');
            $p .= $usrlnk->processTemplate(array('USERNAME' => $a['name'] . '\'s page',
                'PAGE_ID' => '<a href="?pageid=' . $a['id'] . '"'));
        }
        $p .= '</ul>';
    }
}


