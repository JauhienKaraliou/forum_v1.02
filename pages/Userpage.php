<?php

$id= htmlspecialchars($_GET['pageid']); //@todo наверное тоже лучше (int) вместо htmlspecialchars()
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
        $tab = new Template('userPageTabInformation');
        if (Utils::getMessagesByUserId($_GET['pageid'])){
        $messages = Utils::getMessagesByUserId($_GET['pageid']);
        $mesHtml = Utils::getHtmlListOfMessagesForTab($messages);
        $themes = Utils::getThemesByUserId($_GET['pageid']);
        $themHTML = Utils::getHtmlListOfThemesForTab($themes);
        } else {
            $mesHtml = 'Данный пользователь не написал еще ни одного сообщения';
            $themHTML = 'Поэтому и раздел тем пустой';
        }
        $p .= '<br>'.$tab -> processTemplate(array(
                'MES' => $mesHtml,
                'THEM' => $themHTML));
        $msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';
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



