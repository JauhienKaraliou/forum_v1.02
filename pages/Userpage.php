<?php

$id= htmlspecialchars($_GET['pageid']); //@todo наверное тоже лучше (int) вместо htmlspecialchars()
    $sth = DB::getInstance()->prepare('SELECT `id`,`name`,`email`,`about_me` FROM `users` WHERE `id`= :id');
    $sth->execute(array('id'=>$id));
    $userInfo = $sth->fetch(PDO::FETCH_ASSOC);
    if($userInfo == true) {
        if($userInfo['name'] == $_SESSION['username']) {
            $usrpage = new Template('userPageOwn');
            $p = $usrpage->processTemplate($userInfo);
            $tab = new Template('userPageTabInformation');
            $messages = Utils::getMessagesByUserId($_GET['pageid']);
            //var_dump($messages);
            $mesHtml = Utils::getHtmlListOfMessagesForTab($messages);
            //var_dump($mesHtml);
            $p .= '<br>'.$tab -> processTemplate(array(
                'MES' => $mesHtml));
        } else {
            $usrpage = new Template('userpage');
            $p = $usrpage -> processTemplate($userInfo);
            $p .= new Template('userPageTabInformation');
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



