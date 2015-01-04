<?php
$userList = User::getAllUsers();
$p = '<table class="table table-striped table-bordered">';
foreach ($userList as $a) {
    $usrlnk = new Template('userlink');
    $p .= $usrlnk->processTemplate(array('USERNAME' => $a['name'],
        'PAGE_ID' => $a['id']));
}
$p .= '</table>';