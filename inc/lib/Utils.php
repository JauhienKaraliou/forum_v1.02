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
            isset($_POST['passwordrepeat']) AND !empty($_POST));
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
     * Выполняет подстановки в переданный шаблон
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

    public static function sendMail()
    {
        require_once('PHPMailer/class.phpmailer.php');

        $Mail = new PHPMailer();
        $Mail->IsSMTP(); // Use SMTP
        $Mail->Host = "smtp.gmail.com"; // Sets SMTP server
        $Mail->SMTPDebug = 0; // 2 to enable SMTP debug information
        $Mail->SMTPAuth = TRUE; // enable SMTP authentication
        $Mail->Port = 465; // set the SMTP port
        $Mail->Priority = 3; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
        $Mail->CharSet = 'UTF-8';
        $Mail->Encoding = '8bit';
        $Mail->Subject = "Feedback from my-domain.com.";
        $Mail->ContentType = "text/html; charset=utf-8\r\n";
        $Mail->From = MAIL_USER;
        $Mail->Username = MAIL_USER; // SMTP account username
        $Mail->Password = MAIL_PASSWORD; // SMTP account password
        $Mail->FromName = 'My domain';
        $Mail->WordWrap = 900; // RFC 2822 Compliant for Max 998 characters per line

        $Mail->AddAddress('liskorzun@gmail.com');
        $Mail->Send();
        var_dump($Mail->ErrorInfo);
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
        $_SESSION['username']=null;
        $_COOKIE['username']=null;
        $_COOKIE['password']=null;
        setcookie("username",'',time()-3600*25);
        setcookie("password",'',time()-3600*25);
        return true;
    }






}