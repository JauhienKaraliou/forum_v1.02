<?php

$id= htmlspecialchars($_GET['pageid']);
    $sth = DB::getInstance()->prepare('SELECT `id`,`name`,`email`,`about_me` FROM `users` WHERE `id`= :id');
    $sth->execute(array('id'=>$id));
    $userInfo = $sth->fetch(PDO::FETCH_ASSOC);
    if($userInfo == true) {
        if($userInfo['name'] == $_SESSION['username']) {
            $usrpage = new Template('userPageOwn');
            $p = $usrpage->processTemplate($userInfo);
        } else {
            $usrpage = new Template('userpage');
           $p = $usrpage -> processTemplate($userInfo);
        }
    } else {
        $userList = User::getAllUsers();
        $p = '<table class="table table-striped table-bordered">';
        foreach ($userList as $a) {
            $usrlnk = new Template('userlink');
            $p .= $usrlnk->processTemplate(array('USERNAME' => $a['name'],
                'PAGE_ID' => $a['id']));
        }
        $p .= '</table>';
    }



