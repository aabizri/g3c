<?php
namespace Repositories;

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

    /** Helper function: given an associative array of Setters and Data to be set, execute it, and returns an exception if something went wront.
     *
     * For it to work, we need the setters to returns a boolean indicating success(true)/failure(false)
     *
     * @param mixed $element
     * @param array $arr
     * @return void
     * @throws \Exception
     * */
    public static function executeSetterArray($element, array $arr): void {
        foreach ($arr as $setter => $datum) {
            $success = $element->$setter($datum);
            if ($success == false) {
                throw new \Exception("Error with setter ".$setter." with value : ".$datum." (".gettype($datum).")");
            }
        }
    }
}
