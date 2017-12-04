<?php

namespace Entities;

// ONLY FOR DEBUG
require_once("../index.php");

use Helpers\UUID;
use Repositories;

/**
 * Peripheral class is the entity class for Peripherals
 *
 * @package livewell
 * @author Alexandre A. Bizri <alexandre@bizri.fr>
 */
class Peripheral
{
    // Values of this object
    private $uuid;
    private $display_name;
    private $build_date;
    private $add_date;
    private $public_key;
    private $property_id;
    private $room_id;
    private $last_updated;

    /**
     * Peripheral constructor.
     */
    public function __construct()
    {
        // Generate UUID
        $uuid = UUID::v4();

        // Set values in class
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function setUUID(string $uuid): bool
    {
        if (UUID::is_valid($uuid) == false) {
            return false;
        }

        $this->uuid = $uuid;
        return true;
    }

    /**
     * @return string
     */
    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    /**
     * @param string $display_name
     * @return bool
     */
    public function setDisplayName(string $display_name): bool
    {
        $this->display_name = $display_name;
        return true;
    }

    /**
     * @return string
     */
    public function getBuildDate(): ?string
    {
        return $this->build_date;
    }

    /**
     * @param string $build_date
     * @return bool
     */
    public function setBuildDate(string $build_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (strtotime($build_date) > time()) {
            return false;
        }
        $this->build_date = $build_date;
        return true;
    }

    /**
     * @return string
     */
    public function getAddDate(): ?string
    {
        return $this->add_date;
    }

    /**
     * @param string $add_date
     * @return bool
     */
    public function setAddDate(string $add_date): bool
    {
        // Verifier que $add_date est inférieure à la date actuelle
        if (strtotime($add_date) > time()) {
            return false;
        }
        $this->add_date = $add_date;
        return true;
    }

    /**
     * @return string
     */
    public function getPublicKey(): ?string
    {
        return $this->public_key;
    }

    /**
     * @param string $public_key
     * @return bool
     */
    public function setPublicKey(string $public_key): bool
    {
        $this->public_key = $public_key;
        return true;
    }

    /**
     * @return int
     */
    public function getPropertyId(): ?int
    {
        return $this->property_id;
    }

    /**
     * @param int $property_id
     * @return bool
     */
    public function setPropertyId(int $property_id): bool
    {
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return int
     */
    public function getRoomId(): ?int
    {
        return $this->room_id;
    }

    /**
     * @param int $room_id
     * @return bool
     */
    public function setRoomId(int $room_id): bool
    {
        $this->room_id = $room_id;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): ?string
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated(string $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

    /* BUSINESS LOGIC */

    /**
     * Attach the Peripheral to a Room
     *
     * It checks if the Room is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param int $roomID is the ID of the Room this Peripheral should be attached to
     *
     * @return void
     */
    public function attachToRoom(int $roomID): void
    {
        Repositories\Peripherals::attachToRoom($this, $roomID);
    }

    /**
     * Attach the Peripheral to a Property
     *
     * @param int $propertyID is the ID of the Property this Peripheral should be attached to
     *
     * @return void
     */
    public function attachToProperty(int $propertyID): void
    {
        Repositories\Peripherals::attachToProperty($this, $propertyID);
    }
}

function testPeripheralModel()
{
    // Create a new entity
    $p1 = new Peripheral();
    var_dump($p1);

    // Insert it
    Repositories\Peripherals::insert($p1);

    // Attach
    $p1->attachToProperty(1);
    $p1->attachToRoom(1);
}

testPeripheralModel();
