<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 30.12.14
 * Time: 1.43
 */


$userList = User::getAllUsers();
var_dump($userList);
$p.='<ul>';
foreach ($userList as $a) {
    echo $a['id'];
    $p.= Template::getPageElement('userlink', array('USER_ID'=>$a['id'],
                                               'USERNAME'=>$a['name'].'\'s page'));
}
$p.='</ul>';
