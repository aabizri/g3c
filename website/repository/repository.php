<?php
namespace Repository;

use \PDO;
use \Helpers\DB;

abstract class Repository
{
    // Common PDO_Params
    protected static $pdo_params = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);

    // Database
    private static $db_instance = null;

    /** Constructor
     *
     * @return PDO
     */
    public static function db()
    {
        if (self::$db_instance == null) {
            self::$db_instance = DB::getInstance();
        }
        return self::$db_instance;
    }
}
