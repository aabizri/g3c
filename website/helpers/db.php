<?php

namespace Helpers;

use \PDO;

/**
* Database singleton
*
* To call it DB::getInstance()
*/
class DB
{
    private const HOST = "localhost";
    private const DBNAME = "livewell";
    private const USERNAME = "root";
    private const PASSWORD = "";

    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
            $dsn = 'mysql:host='.self::HOST.';dbname='.self::DBNAME;
            self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, $pdo_options);
        }
        return self::$instance;
    }
}
