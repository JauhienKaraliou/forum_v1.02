<?php

/**
 * Class User
 * Класс Пользователя
 */
class User {

    private $userData = array();
    private $resp = true;
    private $errors = array();
    public static $isLogged;
    public static $username;
    public static $userID;
    public static $userStatusID;

    public  function __construct(){
        if (isset($_POST['name'])) {
            $this -> userData['name'] = trim($_POST['name']);
            $_SESSION['name'] = $this -> userData['name'];
        } elseif (isset($_SESSION['name'])) {
            $this -> userData['name'] = trim($_SESSION['name']);
        } else {
            $this -> userData['name'] = "";
        }
        if (isset($_POST['email'])) {
            $this -> userData['email'] = trim($_POST['email']);
            $_SESSION['email'] = $this -> userData['email'];
        } elseif (isset($_SESSION['email'])) {
            $this -> userData['email'] = trim($_SESSION['email']);
        } else {
            $this -> userData['email'] = "";
        }
        $this -> userData['password'] = isset($_POST['password']) ? trim($_POST['password']) : "";
        $this -> userData['passwordrepeat'] = isset($_POST['passwordrepeat']) ? trim($_POST['passwordrepeat']) : "";
        if (isset($_POST['aboutMe'])) {
            $this -> userData['aboutMe'] = trim($_POST['aboutMe']);
            $_SESSION['aboutMe'] = $this -> userData['aboutMe'];
        } elseif (isset($_SESSION['aboutMe'])) {
            $this -> userData['aboutMe'] = trim($_SESSION['aboutMe']);
        } else {
            $this -> userData['aboutMe'] = "";
        }
        $this -> userData['captcha'] = isset($_POST['captcha']) ? trim($_POST['captcha']) : "";
    }

    /**
     * Проверяет уникальность и EMAIL
     * @return mixed
     */
    private function isNotUniqueEmail(){
        $db = DB::getInstance();
        $email = $db -> prepare('SELECT COUNT(users.id) AS count FROM users WHERE users.email = :email');
        $email->execute(array('email' => $this -> userData['email']));
        $count = $email -> fetch(PDO::FETCH_ASSOC);
        return $count['count'];
    }

    /**
     * Проверяет капчу
     * @param $answ
     * @return bool
     */
    private function checkCaptchaAnswer($answ){
        $rightAnsw = isset($_SESSION['captcha'])? $_SESSION['captcha']: '';
        return $answ == $rightAnsw;
    }

    /**
     * Проверяет валидность данных, введенных пользователем при регистрации
     * @return bool
     */
    public function isFormRegisterValid(){
        if (!preg_match('/^([a-zA-Zа-яА-Я0-9_]{3,}\s{0,1})+$/msiu', $this -> userData['name'])) {
            $this -> resp = false;
            $this -> errors['name'] = 'Проверьте ввод имени';
        }
        if (!preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $this -> userData['email'])) {
            $this -> resp = false;
            $this -> errors['email'] = 'Проверьте ввод email';
        }
        if ($this -> isNotUniqueEmail()){
            $this -> resp = false;
            $this -> errors['email'] = 'Такой email уже зарегистрирован';
        }
        if (!preg_match('/\A(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{6,}\z/', $this -> userData['password'])) {
            $this -> resp = false;
            $this -> errors['password'] = 'Пароль должен содержать хотя бы одну большую букву, маленькую букву и цифру и быть не меннее 7 символов';
        }
        if ($this -> userData['password'] !== $this -> userData['passwordrepeat']){
            $this -> resp = false;
            $this -> errors['passwordrepeat'] = 'Пароли не совпадают';
        }
        if(!$this -> checkCaptchaAnswer($this -> userData['captcha'])){
            $this -> resp = false;
            $this -> errors['captcha'] = 'Неправильный ответ';
        }
        return $this -> resp;
    }

    /**
     * Возвращает массив данных пользователя
     * @return array
     */
    public function getUserDataArray(){
        return  $this -> userData;
    }

    /**
     * Возвращает массив ошибок данных при регистрации
     * @return array
     */
    public function getFormRegisterErrors(){
        return $this -> errors;
    }

    /**
     * Возвращает активационный код для пользователя
     * @return mixed
     */
    public function getActivationCode(){
        return $this -> userData['activation'];
    }

    /**
     * Возвращмет email пользователя
     * @return mixed
     */
    public function getUserEmail(){
        return $this -> userData['email'];
    }

    /**
     * Возвращает имя пользователя
     * @return mixed
     */
    public function getUserName(){
        return $this -> userData['name'];
    }

    /**
     * Сохраняет данные пользователя в базу данных
     * @return bool
     */
    public function saveUserData (){
        $db = DB::getInstance();
        $password = md5($this -> userData['password']);
        $this -> userData['activation'] = md5($this -> userData['email']);
        $userDataToSave = $db -> prepare('INSERT INTO users (name, email, password, about_me, activation) VALUES (:name, :email, :password, :about_me, :activation)');
        if($userDataToSave->execute(array(
            'name' => htmlspecialchars($this -> userData['name']),
            'email' => htmlspecialchars($this -> userData['email']),
            'password' => $password,
            'about_me' => htmlspecialchars($this -> userData['aboutMe']),
            'activation' => $this -> userData['activation']
        ))) {
            return true;
        } else {
            return false;
        }
    }

    /**                                 //@todo напиши коммент
     * @param $username
     * @param $password
     * @return bool
     */
    public static function checkIfValid($username, $password)
    {
        $arr['username'] =$username;
        $arr['password'] = md5($password);
        $sth = DB::getInstance()->prepare('SELECT `id`,`ustatus_id` FROM `users` WHERE `name`=:username and `password`=:password');
        $sth->execute($arr);
        $uInfo = $sth->fetch();
        if(isset($uInfo['id']) and $uInfo['ustatus_id']!='3') {
            self::$userID = $uInfo['id'];
            self::$userStatusID = $uInfo['ustatus_id'];
            return true;
        } else {
            return false;
        }
    }

    /**                                //@todo напиши коммент
     * @return array
     */
    public static function getAllUsers()
    {
        $sth = DB::getInstance()->prepare('SELECT DISTINCT `id`,`name` FROM `users`');
        $sth->execute();
        $res= $sth->fetchAll();
        return $res;
    }

    /**                                 //@todo напиши коммент
     * @param $id
     * @return mixed
     */
    public static function getUserNameByID($id)
    {
        $sth = DB::getInstance()-> prepare('SELECT `name` FROM `users` WHERE `id`=:id');
        $sth->execute(array('id'=>$id));
        return $sth -> fetch(PDO::FETCH_ASSOC);
    }
}