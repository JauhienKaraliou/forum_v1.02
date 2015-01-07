<?php

/**
 * Class Utils
 * Класс статических функций
 */
class Utils
{
    /**
     * Проверяет нажата ли определнная кнопка
     * @param $str Название кнопки
     * @return bool
     */
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
     * Производит перенаправление по нужному адресу
     * @param $str Нужные адрес - URL
     */
    public static function redirect($str){
        header('Location: '.$str);
        die();
    }

    /**
     * Формирует URL с нужными параметрами
     * @param array $data Массив необходимых параметров
     * @return string
     */
    public static function getUrl(array $data = array()){
        $url = BASE_URL;
        if(!empty($data)) {
            $url .= '?';
            foreach ($data as $key => $value) {
                $url .= $key.'='.$value.'&';
            }
        }
        return rtrim($url, '&');
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

    /**
     * Выполняет рассылку сообщений
     * @param $to Кому сообщение
     * @param $subject Тема сообщения
     * @param $body Текст сообщения
     * @throws Exception
     * @throws phpmailerException
     */
    public static function sendMail($to, $subject, $body)
    {

        $mail                = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth      = true;
        $mail->SMTPKeepAlive = true;
        $mail->SMTPSecure = 'ssl';
        //$mail->Host          = 'mx1.hostinger.ru';
        //$mail->Port          = 2525;
        $mail->Host          = 'smtp.gmail.com';
        $mail->Port          = 465;
        $mail->Username      = MAIL_USER;
        $mail->Password      = MAIL_PASSWORD;
        $mail->SetFrom(MAIL_USER);
        $mail->CharSet      = 'UTF-8';
        $mail->Subject      = $subject;
        $mail->MsgHTML( $body );
        $mail->AddAddress($to);
        if(!$mail->send()) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Проверяет активационный код пользоваетля
     * @param $code Код пришедший от пользователя
     * @return string
     */
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

    /**
     * Проверяет элементы суперглобального массива $_POST
     * @param $key
     * @return bool
     */
    public static function checkPost($key)
    {
        $res = (isset($_POST[$key]))?true:false;
        return $res;
    }

    /**
     * Проверяет элементы суперглобального массива $_SESSION
     * @param $key
     * @return bool
     */
    public static function checkSession($key)
    {
        $res = (isset($_SESSION[$key]))?true:false;
        return $res;
    }

    /**
     * Проверяет элементы суперглобального массива $_COOKIE
     * @param $key
     * @return bool
     */
    public static function checkCookies($key)
    {
        $res = (isset($_COOKIE[$key]))?true:false;
        return $res;
    }

    /**
     * Проверяет элементы суперглобального массива $_GET
     * @param $key
     * @return bool
     */
    public static function checkGet($key)
    {
        $res = (isset($_GET[$key]))?true:false;
        return $res;
    }

    /**
     *              //@todo напиши коммент, боюсь некорректно сделаю
     */
    public static function logOut()
    {
        $_SESSION['username']= null;
        $_SESSION['userID'] = null;
        $_SESSION['uStatusID'] = null;
        $_SESSION['islogged']=null;
        $_SESSION['PHPSESSID']=null;

        $_COOKIE['PHPSESSID']=null;
        setcookie(session_name(),null,time()-3600*25);
        header('Location: '.BASE_URL);
        die();
    }

    /**
     * Сохраняет данные категории в базу данных
     * @return bool
     */
    public static  function saveCategory(){
        $categoryDataToSave = DB::getInstance() -> prepare('INSERT INTO categories (name, description, user_id) VALUES (:name, :description, :user_id)');
        if($categoryDataToSave->execute(array(
            'name' => htmlspecialchars($_POST['catName']),
            'description' => htmlspecialchars($_POST['catDescription']),
            'user_id' => User::$userID
        ))){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Сохраняет данные темы в базу данных
     * @return bool
     */
    public static  function saveTheme(){
        $themeDataToSave = DB::getInstance() -> prepare('INSERT INTO themes (name, category_id, user_id) VALUES (:name, :category_id, :user_id)');
        if($themeDataToSave->execute(array(
            'name' => htmlspecialchars($_POST['themeName']),
            'category_id' => (int)$_GET['cat_id'],
            'user_id' => User::$userID
        ))){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Сохраняет данные сообщения в базу данных     //@todo проверь :)
     * @return bool
     */
    public static function saveMsg()
    {
        $msgDataToSave = DB::getInstance() -> prepare('INSERT INTO messages (name, user_id, theme_id)
                                                       VALUES (:name, :user_id, :theme_id )');
        $when = date("Y-m-d H:i:s");
        if ($msgDataToSave-> execute(array(
            'name' => htmlspecialchars($_POST['messagetext']).'<br><small>'.$when.'</small>',    // да, этот костыль
            // дописывает дату создания в сообщение, т.к. в базе это не предусмотрелось
            'user_id' => User::$userID,
            'theme_id' => htmlspecialchars($_GET['theme_id']) //@todo наверное стоит htmlspecialchars() поменять на (int)
        ))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Извлекает все категории из базы
     * @return array
     */
    public static function getAllCategories(){
        $categories = DB::getInstance()->prepare('SELECT id, name, description FROM `categories`');
        $categories->execute();
        return $categories->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Формирует HTML из списка категорий
     * @param array $data Массив категорий
     * @return string
     */
    public static function getHtmlListOfCategories(array $data = array()){
        $p = '';
        foreach($data as $row){
            $catTpl = new Template('itemOfCategory');
            $p .= $catTpl->processTemplate(array(
                'URL' =>  Utils::getUrl(array('cat_id' => $row['id'])),
                'CAT_NAME' => $row['name'],
                'CAT_DESCRIPTION' => $row['description']
            ));
        }
        return $p;
    }

    /**
     * Извлекает все темы определенной категории
     * @param $id ID категории
     * @return array
     */
    public static function getThemesByIdOfCategory($id){
        $themes = DB::getInstance()->prepare('SELECT id, name  FROM `themes` WHERE `themes`.`category_id` = :id');
        $themes -> execute(array('id' => $id));
        return $themes->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Формирует HTML из массива тем
     * @param array $data
     * @return string
     */
    public static function getHtmlListOfThemes(array $data = array()){
        $p = '';
        foreach($data as $row){
            $themeTpl = new Template('itemOfTheme');
            $id = (int) $row['id'];
            $p .= $themeTpl->processTemplate(array(
                'THEME_ID' => $id,
                'THEME_NAME' => $row['name'],
                'CAT_ID' => $_GET['cat_id'],
            ));
        }
        return $p;
    }

    /**
     * Извлекает сообщения определенной темы
     * @param $id ID темы
     * @return array
     */
    public static function getMessagesByIDTheme($id)
    {
        $sth = DB::getInstance()->prepare('SELECT * FROM `messages` WHERE `theme_id`=:id');
        $sth -> execute(array('id'=>$id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Извлекает все сообщения определенного пользователя
     * @param $id ID пользователя
     * @return array
     */
    public static function getMessagesByUserId($id){
        $sth = DB::getInstance()->prepare('SELECT `messages`.`name` AS mes, `themes`.`name` AS them, `themes`.`category_id` AS cat_id, `themes`.`id` AS theme_id FROM `messages`, `themes` WHERE `messages`.`user_id`=:id AND `themes`.`id` =`messages`.`theme_id` ORDER BY theme_id');
        $sth -> execute(array('id'=>$id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Формирует HTML из массива сообщений
     * @param array $data
     * @return string
     */
    public static function getHtmlListOfMessagesForTab(array $data = array()){
        $p = '';
        foreach ($data as $row) {
            $msgTpl = new Template('itemOfCategory');
            $p.= $msgTpl->processTemplate( array(
                'CAT_DESCRIPTION'=> $row['mes'],
                'CAT_NAME'=>'В Теме: '.$row['them'],
                'URL' =>  Utils::getUrl(array('cat_id' => $row['cat_id'], 'theme_id' => $row['theme_id']))
            ));
        }
        return $p;
    }

    /**
     * Извлекает все Темы, в которых писал определенный пользователь
     * @param $id ID пользователя
     * @return array
     */
    public static function getThemesByUserId($id){
        $sth = DB::getInstance()->prepare('SELECT `themes`.`name` AS them, `themes`.`category_id` AS cat_id, `themes`.`id` AS theme_id FROM `messages`, `themes` WHERE `messages`.`user_id`=:id AND `themes`.`id` =`messages`.`theme_id` GROUP BY theme_id');
        $sth -> execute(array('id'=>$id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Формирует HTML из массива тем
     * @param array $data
     * @return string
     */
    public static function getHtmlListOfThemesForTab(array $data = array()){
        $p = '';
        foreach ($data as $row) {
            $msgTpl = new Template('itemOfCategory');
            $p.= $msgTpl->processTemplate( array(
                'CAT_DESCRIPTION'=> '',
                'CAT_NAME'=> $row['them'],
                'URL' =>  Utils::getUrl(array('cat_id' => $row['cat_id'], 'theme_id' => $row['theme_id']))
            ));
        }
        return $p;
    }

    /**
     * Формирует HTML из массива сообщений
     * @param array $data
     * @return string
     */
    public static function getHtmlListOfMessages( array $data = array()){
        $p = '<br>';
        foreach ($data as $row) {
            $msgTpl = new Template('itemOfMsg');
            $username = User::getUserNameByID($row['user_id']);
            $p.= $msgTpl->processTemplate( array(
                'name'=> $row['name'],
                'username'=>$username['name']
            ));
        }
        return $p;
    }

    /**
     * @return mixed    //@todo напиши коммент
     */
    public static function getHtmlFormAddMsg ()
    {
        $form = new Template('formAddMessage');
        return $form->processTemplate();
    }


}