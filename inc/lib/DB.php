<?php
/**
 * Created by PhpStorm.
 * User: ALEX
 * Date: 27.12.2014
 * Time: 1:27
 */
class DB extends PDO{

    protected static $_instance;

    /**
     *  тут  я попробовал завернуть в try-catch так должны ловиться ошибки подключения
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct($dsn = DB_DSN , $username = DB_USER , $password = DB_PASSWORD, array $options = array())
    {
        try {
            parent::__construct($dsn, $username, $password, $options);

        } catch (PDOException $e) {
            print "Error:".$e->getMessage().'<br>';
            die();
        }

    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
}