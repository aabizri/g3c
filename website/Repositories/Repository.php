<?php

namespace Repositories;

use Helpers\DB;
use PDO;

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
    public static function db(): PDO
    {
        if (self::$db_instance == null) {
            self::$db_instance = DB::getPDO();
        }
        return self::$db_instance;
    }
}
