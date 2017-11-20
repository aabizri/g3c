<?php

require_once("../connection.php");
require_once("../uuid.php");

// Object peripheral
class Peripheral
{
    // INT
    private $uuid;
    private $display_name;
    private $build_date;
    private $add_date;
    private $public_key;
    private $property_id;
    private $room_id;

    // Create a new one
    public static function create($build_date, $public_key)
    {
        $instance = new self();

        // Generate UUID
        $uuid = UUID::v4();

        // Set values in class
        $instance->uuid = $uuid;
        $instance->build_date = $build_date;
        $instance->public_key = $public_key;

        // Execute query
        // SQL Request
        $sql = "INSERT INTO peripherals (uuid, build_date, public_key) VALUES (:uuid, :build_date, :public_key)";
        // Get DB instance
        $db = DB::getInstance();
        // Prepare statement
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // Execute it
        $sth->execute(array(':uuid' => $uuid, ':build_date' => $build_date, ':public_key' => $public_key));
    }

    // Retrieve a Peripheral given its UUID
    // Returns null if nothing is found
    public static function find(string $uuid)
    {
        $instance = new self();

        // SQL Request
        $sql = "SELECT display_name, build_date, add_date, public_key, property_id, room_id FROM peripherals WHERE uuid = :uuid" ;
        // Get DB instance
        $db = DB::getInstance();
        // Prepare statement
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // Execute it
        $sth->execute(array(':uuid' => $uuid));
        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);
        // If nil, we return nil
        if ($data == null) {
            echo "no data found";
            return null;
        }
        // Store
        $instance->display_name = $data["display_name"];
        $instance->build_date = $data["build_date"];
        $instance->add_date = $data["add_date"];
        $instance->public_key = $data["public_key"];
        $instance->property_id = $data["property_id"];
        $instance->room_id = $data["room_id"];
        // Return
        return $instance;
    }

    // Attach the Peripheral to a Property
    public function attachToProperty(int $propertyID)
    {
        // TODO
        throw new Exception("not yet implemented");
    }

    // Attach the Peripheral to a Room
    public function attachToRoom(int $roomID)
    {
        // Start transaction on peripheral
        // Check propertyID associated with that peripheral
        // If there is none, throw exception

        // Retrieve propertyID associated with that room
        // If it's not the same as the one associated with this peripheral, throw exception

        //
        // TODO
        throw new Exception("not yet implemented");
    }

    /*
	@var int
	*/
    public function getUUID()
    {
        return $this->$uuid;
    }
    public function getName()
    {
        return $this->$name;
    }
    public function setDisplayName(string $display_name)
    {
        // SQL Request
        $sql = "UPDATE peripherals SET display_name = :display_name WHERE uuid = :uuid";
        // Get DB instance
        $db = DB::getInstance();
        // Prepare statement
        $sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        // Execute it
        $sth->execute(array(':display_name' => $display_name, ':uuid' => $this->$uuid));
        // Update locally if there isn't any problem
        $this->$display_name = $display_name;
    }
}
