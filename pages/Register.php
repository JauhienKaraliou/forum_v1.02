<?php

$user = new User();
$p = new Template('formRegister');
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

                if (Utils::sendMail($to, $subject, $body)){
                    $_SESSION['msg'] = "На ваш почтовый ящик отправлено письмо.<br> Для активации аккаунта, пройдите по ссылке в письме";
                    Utils::redirect(BASE_URL);
                }else {
                    $_SESSION['msg'] = "Мы не смогли отправить письмо на ваш ящик";
                    Utils::redirect(BASE_URL);
                }

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
$_SESSION['msg'] = "Введите персональные данные для регистрации";
