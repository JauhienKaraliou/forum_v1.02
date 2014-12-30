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
        echo $a['id'];
        $p .= Template::getPageElement('userlink', array('USERNAME' => $a['name'] . '\'s page', 'PAGE_ID' => '<a href="?pageid=' . $a['id'] . '"'));
    }
    $p .= '</ul>';
} elseif (Utils::checkGet('pageid')) {
    //сравніть ид пользователя с ид запрашиваемой страницы, и вызвать или изменяемый или неизменяемый шаблон
    // страницы, эту же проверку провести, при приёме данных
    $p.=Template::getPageElement('userpage');
}