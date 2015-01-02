<?php

    $p = new Template('formRegister');
var_dump($user);
$user = new User();
var_dump($user);
    if(Utils::isFormRegisterSubmitted()){
        $userData = $user-> getUserDataArray();
        $validateFormResult = $user -> isFormRegisterValid();
        if($validateFormResult!== true) {
            $formErrors = $user -> getFormRegisterErrors();
            $p -> processTemplateErrorOutput($formErrors);
        } else {
            if($user -> saveUserData()){
                $activation = $user -> getActivationCode();
                $body = 'Здравствуйте!<br>Мы должны убедиться в том, что вы человек. Пожалуйста, подтвердите адрес вашей электронной почты, и можете начать использовать ваш аккаунт на сайте.<br><br><a href="'.BASE_URL.'?code='. $activation . '">'. BASE_URL .'?code='.$activation.'</a>';
                $subject = "Подтверждение электронной почты на IT Форуме";
                $to = $user -> getUserEmail();
                $_SESSION['msg'] = 'На ваш почтовый ящик отправлено письмо. Для активации аккаунта, пройдите по ссылке в письме';
                Utils::sendMail($to, $subject, $body);
                header('Location: '.$_SERVER['REQUEST_URI']);
                die;
            } else {
                $msg = 'Ошибка сохранения';
            }
        }
    }
    else {
        $userData = $user-> getUserDataArray();
    }
    $p -> processTemplate($userData);
    $p = $p -> processTemplate(array('CAPTCHA' => Utils::generateCaptcha()));
