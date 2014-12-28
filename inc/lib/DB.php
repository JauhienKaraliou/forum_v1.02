<?php
/**
 * Created by PhpStorm.
 * User: ALEX
 * Date: 27.12.2014
 * Time: 1:27
 */
class DB extends PDO{

    protected static $_instance;

    public function __construct($dsn = DB_DSN , $username = DB_USER , $password = DB_PASSWORD, array $options = array()){
        parent::__construct($dsn, $username, $password, $options);
      }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
}