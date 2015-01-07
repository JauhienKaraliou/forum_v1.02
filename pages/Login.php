<?php

$p = new Template('formlogin');
$p = $p->processTemplate(array('WRONG_LOGIN_MESSAGE'=>''));
$_SESSION['msg'] = "Введите свой логин и пароль";
$msg = '<a href="'.BASE_URL.'" class="btn btn-inverse">ВСЕ КАТЕГОРИИ</a>';