<?php

class User {

    private $userData = array();
    private $resp = true;
    private $errors = array();

    /**
     * может конструкт надо переназвать в registerUser?
     * я понимаю этот метод при создании объекта формирует массив данных который затем записывается а БД при
     * регистрации пользователя
     *
     * пока не дошла но додумаю !!!!!!!!!!!!!!!!
     *
     */
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

    public function getUserDataArray(){
        return  $this -> userData;
    }

    private function isNotUniqueEmail(){
        $db = DB::getInstance();
        $email = $db -> prepare('SELECT COUNT(users.id)AS count FROM users WHERE users.email = :email');
        $email->execute(array('email' => $this -> userData['email']));
        $count = $email -> fetch(PDO::FETCH_ASSOC);
        var_dump($email -> errorInfo());
        var_dump($count);
        if (($count[0]['count'] == true) and ($count[0]['count'] == NULL)) {
            return true;
        } else {
            return false;
        }
    }

    private function checkCaptchaAnswer($answ){
        $rightAnsw = isset($_SESSION['captcha'])? $_SESSION['captcha']: '';
        return $answ == $rightAnsw;
    }

    public function isFormRegisterValid(){
        if (!preg_match('/^([a-zA-Zа-яА-Я0-9_]{3,}\s{0,1})+$/msiu', $this -> userData['name'])) {
            $this -> resp = false;
            $this -> errors['name'] = 'Проверьте ввод имени';
        }
        if (!preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $this -> userData['email'])) {
            $this -> resp = false;
            $this -> errors['email'] = 'Проверьте ввод email';
        }
        /*if ($this -> isNotUniqueEmail(new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD))){
            $this -> resp = false;
            $this -> errors['email'] = 'Такой email уже зарегистрирован';
        }*/
        if (!preg_match('/\A(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{6,}\z/', $this -> userData['password'])) {
            $this -> resp = false;
            $this -> errors['password'] = 'Пароль должен содержать хотя бы одну большую букву, маленькую букву и цифру';
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

    public function getFormRegisterErrors(){
        return $this -> errors;
    }

    public function saveUserData (){
        $db = DB::getInstance();
        $password = md5($this -> userData['password']);
        $userDataToSave = $db -> prepare('INSERT INTO users (name, email, password, about_me) VALUES (:name, :email, :password, :about_me)');
        //var_dump($db->errorInfo());
       if($userDataToSave->execute(array(
            'name' => $this -> userData['name'],
            'email' => $this -> userData['email'],
            'password' => $password,
            'about_me' => $this -> userData['aboutMe']
        ))) {
           //var_dump($userDataToSave->errorinfo());
           return true;
       } else {
           return false;
       }
    }

    public static function checkIfValid($username, $password)
    {
        $arr['username'] =$username;
        $arr['password'] = md5($password);
        $sth = DB::getInstance()->prepare('SELECT `id` FROM `users` WHERE `name`=:username and `password`=:password');
        $sth->execute($arr);
        $res=($sth->fetchAll())?true:false;
        return $res;
    }

    public static function getAllUsers()
    {
        $sth = DB::getInstance()->prepare('SELECT DISTINCT `id`,`name` FROM `users`');
        $sth->execute();
        $res= $sth->fetchAll();
        return $res;
    }

   // public static function g




}