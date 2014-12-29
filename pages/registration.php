<?php
/**
 * Created by PhpStorm.
 * User: jauhien
 * Date: 28.12.14
 * Time: 21.07
 */ //тут вся твоя воля))


$form = new Template('LogInOrRegisterButtons');
$msg = "Добро пожаловать на IT Форум!<br>Вы не аторизованы, поэтому не можете оставлять комментарии.<br>
Пожалуйста авторизируйтесь или зарегистрируйтесь.";



if(Utils::isButtonPressed('Register')){
    $form = new Template('formRegister');
    if(Utils::isFormRegisterSubmitted()){
        $userData = $user-> getUserDataArray();
        $validateFormResult = $user -> isFormRegisterValid();
        if($validateFormResult!== true) {
            $formErrors = $user -> getFormRegisterErrors();
            $form -> processTemplateErrorOutput($formErrors);
        } else {
            if($user -> saveUserData($db)){
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
    $form -> processTemplate($userData);
    $form = $form -> processTemplate(array('CAPTCHA' => Utils::generateCaptcha()));
}

var_dump(Utils::sendMail());

$page = $page -> processTemplate(array(
    'FORM' => $form,
    'MSG' => $msg,

));

echo $page;

/*$formDataObj = new FormData();
$formData = $formDataObj -> getFormDataArray();

$db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
$storage = new Storage($db);

if(Utils::isFormSubmitted()){
    $formDataObj -> isFormValid();
    $validateFormResult = $formDataObj -> getFormDataResp();
    if($validateFormResult!== true) {
        $formErrors = $formDataObj -> getFormDataErrors();
        $form -> processTemplateErrorOutput($formErrors);
    } else {
        $arrayData = $formDataObj -> getNecessaryDataToSave();
        if($storage -> saveMessage($arrayData)){
            header('Location: '.$_SERVER['REQUEST_URI']);
            die;
        } else {
            $msg = 'Ошибка сохранения';
        }
    }
}

$form -> processTemplate($formData);
$form = $form -> processTemplate(array('CAPTCHA' => Utils::generateCaptcha()));

if ($storage -> isExistsMessageData()){
    $currentPage = Utils::getCurrentPage();
    $listOfMessages = Utils::getListOfMessages($currentPage, new Template('message'), $storage);
    $paginator = Utils::pagers($currentPage, new Template('pagersprimary'),  new Template('pagerswarning'), $storage);
} else {
    $listOfMessages = 'Пока нет ни одного сообщения';
    $paginator = 'Будьте первым гостем!';
}*/
