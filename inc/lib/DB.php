<?php

class DB extends PDO{

    protected static $_instance;

    /**
     * Создает подключение к базе данных
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

    /**
     * Возвращает единственное подключение к базе данных
     * @return DB
     */
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
}