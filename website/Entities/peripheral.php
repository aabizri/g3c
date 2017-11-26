<?php

namespace Entities;

// ONLY FOR DEBUG
require_once("../index.php");

use \Repositories;
use \Helpers\UUID;

/**
 * Peripheral class is the entity class for Peripherals
 *
 * @package livewell
 * @author Alexandre A. Bizri <alexandre@bizri.fr>
 */
class Peripheral
{
    // Values of this object
    public $uuid;
    public $display_name;
    public $build_date;
    public $add_date;
    public $public_key;
    public $property_id;
    public $room_id;
    public $last_updated;

    public function __construct()
    {
        // Generate UUID
        $uuid = UUID::v4();

        // Set values in class
        $this->uuid = $uuid;
    }

    /**
     * Pull the new values
     *
     * This is a helper allowing us to call the repository directly
     *
     * @return void
     */
    public function pull()
    {
        Repositories\Peripherals::pull($this);
    }

    /**
     * Push the new values
     *
     * This is a helper allowing us to call the repository directly
     *
     * @return void
     */
    public function push() {
        Repositories\Peripherals::push($this);
    }

    /**
     * Syncs a Model\Peripheral with the database, executing a Pull or a Push on a last_updated timestamp basis
     *
     * @return void
     *
     * @throws \Exception if not found
     */
    public function sync() {
        Repositories\Peripherals::sync($this);
    }

    /**
     * Attach the Peripheral to a Room
     *
     * It checks if the Room is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param int $roomID is the ID of the Room this Peripheral should be attached to
     *
     * @return void
     */
    public function attachToRoom(int $roomID) {
        Repositories\Peripherals::attachToRoom($this,$roomID);
    }

    /**
     * Attach the Peripheral to a Property
     *
     * @parem Model\Peripheral $p is the Peripheral to be attached to a Property
     * @param int $propertyID is the ID of the Property this Peripheral should be attached to
     *
     * @return void
     */
    public function attachToProperty(int $propertyID) {
        Repositories\Peripherals::attachToProperty($this,$propertyID);
    }
}

function testPeripheralModel()
{
    $p1 = new Peripheral();
    var_dump($p1);
    $p1->attachToProperty(1);
    var_dump($p1);
    $p1->attachToRoom(1);
}

testPeripheralModel();
