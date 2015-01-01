<?php

/**
 * Class Utils
 * Класс статических функций
 */
class Utils
{
    public static function isButtonPressed($str)
    {
        if (isset($_POST['action']) and $_POST['action'] == $str) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Проверяет пришли ли нужные данные
     * @return bool
     */
    public static function isFormRegisterSubmitted()
    {
        return (isset($_POST['name']) AND isset($_POST['email']) AND isset($_POST['password']) AND
            isset($_POST['passwordrepeat']));
    }


    /**
     * Генерирует капчу. Возвращает вопрос. Ответ устанавливает в сессию
     * @return string
     */
    public static function generateCaptcha()
    {
        $answ = rand(1, 20);
        $marker = rand(0, 1) ? '+' : '-';

        $b = rand(1, $answ);
        switch ($marker) {
            case '+':
                $a = $answ - $b;
                break;
            case '-':
                $a = $answ + $b;
                break;
        }
        $_SESSION['captcha'] = $answ;
        return $a . ' ' . $marker . ' ' . $b;
    }

    /**
     * Выполняет подстановки данных в переданный шаблон
     * @param $tpl string - строка с макросами подстановки вида {{NAME}}
     * @param array $data - массив подстановок вида array('NAME' => 'code')
     * @return string
     */
    public static function processTemplate($tpl, array $data = array())
    {
        foreach ($data as $key => $val) {
            $tpl = str_replace('{{' . $key . '}}', $val, $tpl);
        }
        return $tpl;
    }

    public static function sendMail($to, $subject, $body)
    {

        $mail                = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth      = true;
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host          = 'smtp.gmail.com';
        $mail->Port          = 465;
        $mail->Username      = MAIL_USER;
        $mail->Password      = MAIL_PASSWORD;
        $mail->SetFrom(MAIL_USER);
        $mail->CharSet      = 'UTF-8';
        $mail->Subject      = $subject;
        $mail->MsgHTML( $body );
        $mail->AddAddress($to);
        $mail->send();
        var_dump($mail->ErrorInfo);
    }

    public static function checkActivationCode($code){
        $db = DB::getInstance();
        $idUser = $db -> prepare('SELECT `users`.`id`,`users`.`ustatus_id` AS status FROM `users` WHERE `users`.`activation` = :code');
        if($idUser -> execute(array('code' => $code))){
            $data = $idUser -> fetchAll(PDO::FETCH_ASSOC);
            if($data[0]['status'] == 3 and count ($data) == 1){
                $result = $db -> prepare('UPDATE `users` SET `users`.`ustatus_id` = :user WHERE `users`.`activation` = :code');
                $result -> execute(array('user' => 2, 'code' => $code));
                return $_SESSION['msg'] = 'Ваш аккаунт активирован. Теперь вы можете авторизоваться на форуме';
            }elseif ($data[0]['status'] == 2){
                return $_SESSION['msg'] = 'Ваш аккаунт уже активирован, нет необходимости активировать его снова';
            }else {
                return $_SESSION['msg'] = 'Неверный код активации';
            }
        }
    }

    public static function checkPost($key)
    {
        $res = (isset($_POST[$key]))?true:false;
        return $res;
    }

    public static function checkSession($key)
    {
        $res = (isset($_SESSION[$key]))?true:false;
        return $res;
    }

    public static function checkCookies($key)
    {
        $res = (isset($_COOKIE[$key]))?true:false;
        return $res;
    }

    public static function checkGet($key)
    {
        $res = (isset($_GET[$key]))?true:false;
        return $res;
    }

    public static function logOut()
    {
        $_SESSION['islogged']=null;
        $_SESSION['PHPSESSID']=null;
        $_SESSION['username']=null;
        $_COOKIE['PHPSESSID']=null;
        $_COOKIE['password']=null;
        setcookie("PHPSESSID",null,time()-3600*25);
        setcookie("password",'',time()-3600*25);
        return true;
    }
}