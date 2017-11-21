<?php

require_once("../connection.php");
require_once("../uuid.php");

/**
* PeripheralModel class is the Model class for Peripherals
*
* @package livewell
* @author Alexandre A. Bizri <alexandre@bizri.fr>
*/
class PeripheralModel
{
    // Database
    private static $db = null;

    // SQL Queries
    private const ATTACH_TO_ROOM_SQL = "UPDATE peripherals
        SET room_id = :room_id
        WHERE uuid = :uuid AND property_id =
            (SELECT property_id
                FROM rooms
                WHERE id = :room_id)";
    private const ATTACH_TO_PROPERTY_SQL = "UPDATE peripherals
        SET property_id = :property_id
        WHERE uuid = :uuid";
    private const CREATE_SQL = "INSERT INTO peripherals (uuid, build_date, public_key)
        VALUES (:uuid, :build_date, :public_key)";
    private const PULL_SQL =  "SELECT display_name, build_date, add_date, public_key, property_id, room_id, last_updated
        FROM peripherals
        WHERE uuid = :uuid";
    private const PUSH_SQL = "UPDATE peripherals
        SET display_name = :display_name, build_date = :build_date, add_date = :add_date, property_id = :property_id, room_id = :room_id, last_updated = :last_updated
        FROM peripherals
        WHERE uuid = :uuid";

    // Prepared statements
    private static $attachToRoomStatement = null;
    private static $attachToPropertyStatement = null;
    private static $createStatement = null;
    private static $pullStatement = null;

    // Constructor initialises prepared statements
    private function __construct()
    {
        if (self::$db == null) {
             self::$db = DB::getInstance();
        }

        $pdo_params = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);

        if (self::$attachToRoomStatement == null) {
            self::$attachToRoomStatement = self::$db->prepare(self::ATTACH_TO_ROOM_SQL, $pdo_params);
        }

        if (self::$attachToPropertyStatement == null) {
            self::$attachToPropertyStatement = self::$db->prepare(self::ATTACH_TO_PROPERTY_SQL, $pdo_params);
        }

        if (self::$createStatement == null) {
            self::$createStatement = self::$db->prepare(self::CREATE_SQL, $pdo_params);
        }

        if (self::$pullStatement == null) {
            self::$pullStatement = self::$db->prepare(self::PULL_SQL, $pdo_params);
        }
    }

    // Values of this object
    public $uuid;
    public $display_name;
    public $build_date;
    public $add_date;
    public $public_key;
    public $property_id;
    public $room_id;
    public $last_updated;

    /**
    * Create a new Peripheral
    *
    * @param string $build_date is the date (YY-MM-DD) of manufacture of the item
    * @param string $public_key is the public key associated with that peripheral for validation
    *
    * @return Peripheral
    */
    public static function create(string $build_date, string $public_key)
    {
        $instance = new self();

        // Generate UUID
        $uuid = UUID::v4();

        // Set values in class
        $instance->uuid = $uuid;

        // Execute creation query
        self::$createStatement->execute(array(':uuid' => $uuid, ':build_date' => $build_date, ':public_key' => $public_key));

        // Execute update query
        $instance->pull();

        // Return
        return $instance;
    }

    /**
    * Retrieve a Peripheral given its UUID.
    *
    * @param string $uuid is the UUID of the Peripheral to be found
    *
    * @return void null if nothing is found
    */
    public static function find(string $uuid)
    {
        $instance = new self();
    
        // Set UUID
        $instance->uuid = $uuid;

        // Call update
        $instance->pull();

        // Return
        return $instance;
    }

    /**
    * Pull the new values
    *
    * @return void
    */
    public function pull()
    {
        // Execute query
        $sth = self::$db->prepare(self::PULL_SQL, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':uuid' => $this->uuid));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we return nil
        if ($data == null) {
            echo "no data found";
            return null;
        }

        // Store
        $this->display_name = $data["display_name"];
        $this->build_date = $data["build_date"];
        $this->add_date = $data["add_date"];
        $this->public_key = $data["public_key"];
        $this->property_id = $data["property_id"];
        $this->room_id = $data["room_id"];
        $this->last_updated = $data["last_updated"];
    }

    /**
    * Attach the Peripheral to a Property
    *
    * @param int $propertyID is the ID of the Property this Peripheral should be attached to
    *
    * @return void
    */
    public function attachToProperty(int $propertyID)
    {
        self::$attachToPropertyStatement->execute(array(':property_id' => $propertyID, ':uuid' => $this->uuid));
    }

    /**
    * Attach the Peripheral to a Room
    *
    * It checks if the Room is linked to the same Property as the Peripheral, returns an Exception if it fails.
    *
    * @param int $propertyID is the ID of the Property this Peripheral should be attached to
    *
    * @return void
    */
    public function attachToRoom(int $roomID)
    {
        $sth = self::$attachToRoomStatement;
        $sth->execute(array('room_id' => $roomID, ':uuid' => $this->uuid));
        
        // Check for sane row count of affected rows
        $rc = $sth->rowCount();
        switch ($rc) {
            case 0:
                throw new Exception("Conditions not set, are the peripheral & room attached to the right property ?");
                break;
            case 1: // Perfect, continue
                break;
            default:
                throw new Exception("More than 1 affected record, this is not normal, aborting !");
                break;
        }
    }
}

function testPeripheralModel()
{
    $p1 = PeripheralModel::create("2017-11-20", "");
    var_dump($p1);
    $p1->attachToProperty(1);
    var_dump($p1);
    $p1->attachToRoom(1);
}

testPeripheralModel();
