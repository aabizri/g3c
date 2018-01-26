<?php

namespace Entities;

use Helpers\UUID;
use Repositories;

/**
 * Peripheral class is the entity class for Peripherals
 *
 * @package livewell
 * @author Alexandre A. Bizri <alexandre@bizri.fr>
 */
class Peripheral extends Entity
{
    /* PROPERTIES */

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $display_name;

    /**
     * @var string (MM-DD)
     */
    private $build_date;

    /**
     * @var string (ISO 8601)
     */
    private $add_date;

    /**
     * @var string
     */
    private $public_key;

    /**
     * @var int
     */
    private $property_id;

    /**
     * @var Property
     */
    private $property;

    /**
     * @var int
     */
    private $room_id;

    /**
     * @var Room
     */
    private $room;

    /**
     * @var float
     */
    private $last_updated;

    /* CONSTRUCTOR */

    /**
     * Peripheral constructor.
     */
    public function __construct()
    {
        // Generate UUID
        $this->setUUID(UUID::v4());
    }

    /* GETTERS AND SETTERS */

    public function getID(): string
    {
        return $this->getUUID();
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
     *
     */
    public function setUUID(string $uuid): void
    {
        if (UUID::is_valid($uuid) == false) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $uuid, "invalid UUID !");
        }

        $this->uuid = $uuid;

    }


    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    /**
     * @param string|null $display_name
     *
     */
    public function setDisplayName(?string $display_name): void
    {
        $this->display_name = $display_name;

    }

    /**
     * @return string|null
     */
    public function getBuildDate(): ?string
    {
        return $this->build_date;
    }

    /**
     * @param string|null $build_date
     *
     */
    public function setBuildDate(?string $build_date): void
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (strtotime($build_date) > time()) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $build_date, "build date sooner than right now");
        }
        $this->build_date = $build_date;

    }

    /**
     * @return string|null
     */
    public function getAddDate(): ?string
    {
        return $this->add_date;
    }

    /**
     * @param string|null $add_date
     *
     */
    public function setAddDate(?string $add_date): void
    {
        // Verifier que $add_date est inférieure à la date actuelle
        if (strtotime($add_date) > time()) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $add_date, "add date sooner than right now");
        }
        $this->add_date = $add_date;

    }

    /**
     * @return string|null
     */
    public function getPublicKey(): ?string
    {
        return $this->public_key;
    }

    /**
     * @param string|null $public_key
     *
     */
    public function setPublicKey(?string $public_key): void
    {
        $this->public_key = $public_key;

    }

    /**
     * @return int|null
     */
    public function getPropertyID(): ?int
    {
        return $this->property_id;
    }

    /**
     * @param int|null $property_id
     *
     */
    public function setPropertyID(?int $property_id): void
    {
        $this->property_id = $property_id;

    }

    /**
     * @return Property|null
     */
    public function getProperty(): ?Property
    {
        if ($this->property === null) {
            if ($this->property_id === null) {
                return null;
            }
            $this->room = (new \Queries\Properties)->retrieve($this->property_id);
        }
        return $this->property;
    }

    /**
     * @param Property|null $p
     *
     */
    public function setProperty(?Property $p): void
    {
        $this->property = $p;
        if ($p === null) {
            $this->property_id = null;
        } else {
            $this->property_id = $p->getID();
        }

    }

    /**
     * @return int|null
     */
    public function getRoomID(): ?int
    {
        return $this->room_id;
    }

    /**
     * @param int|null $room_id
     *
     */
    public function setRoomID(?int $room_id): void
    {
        $this->room_id = $room_id;

    }

    /**
     * @return Room|null
     * @throws \Exception
     */
    public function getRoom(): ?Room
    {
        if ($this->room === null) {
            if ($this->room_id === null) {
                return null;
            }
            $this->room = (new \Queries\Rooms)->retrieve($this->room_id);
        }
        return $this->room;
    }

    /**
     * @param Room|null $r
     *
     */
    public function setRoom(?Room $r): void
    {
        $this->room = $r;
        if ($r === null) {
            $this->room_id = null;
        } else {
            $this->room_id = $r->getID();
        }

    }

    /**
     * @return float|null
     */
    public function getLastUpdated(): ?float
    {
        return $this->last_updated;
    }

    /**
     * @param float $last_updated
     *
     */
    public function setLastUpdated(float $last_updated): void
    {
        $this->last_updated = $last_updated;

    }

    /* BUSINESS LOGIC */

    /**
     * Attach the Peripheral to a Room.php
     *
     * It checks if the Room.php is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param int $roomID is the ID of the Room.php this Peripheral should be attached to
     *
     * @return void
     *
     * @throws \Exception
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
     *
     * @throws \Exception
     */
    public function attachToProperty(int $propertyID): void
    {
        Repositories\Peripherals::attachToProperty($this, $propertyID);
    }

}