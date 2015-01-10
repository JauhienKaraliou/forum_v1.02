<?php

$userList = User::getAllUsers();
$p = '<table class="table table-striped table-bordered">';
foreach ($userList as $a) {
    $usrlnk = new Template('userlink');
    $p .= $usrlnk->processTemplate(array(
        'USERNAME' => $a['name'],
        'URL_USER_ID' =>  Utils::getUrl(array('pageid' => $a['id']))));
}
$p .= '</table>';
$msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';