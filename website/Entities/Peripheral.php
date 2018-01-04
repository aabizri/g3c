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
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->display_name;
    }

    /**
     * @param string|null $display_name
     * @return bool
     */
    public function setDisplayName(?string $display_name): bool
    {
        $this->display_name = $display_name;
        return true;
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
     * @return bool
     */
    public function setBuildDate(?string $build_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (strtotime($build_date) > time()) {
            return false;
        }
        $this->build_date = $build_date;
        return true;
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
     * @return bool
     */
    public function setAddDate(?string $add_date): bool
    {
        // Verifier que $add_date est inférieure à la date actuelle
        if (strtotime($add_date) > time()) {
            return false;
        }
        $this->add_date = $add_date;
        return true;
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
     * @return bool
     */
    public function setPublicKey(?string $public_key): bool
    {
        $this->public_key = $public_key;
        return true;
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
     * @return bool
     */
    public function setPropertyID(?int $property_id): bool
    {
        $this->property_id = $property_id;
        return true;
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
            $this->room = \Repositories\Properties::retrieve($this->property_id);
        }
        return $this->property;
    }

    /**
     * @param Property|null $p
     * @return bool
     */
    public function setProperty(?Property $p): bool
    {
        $this->property = $p;
        if ($p === null) {
            $this->property_id = null;
        } else {
            $this->property_id = $p->getID();
        }
        return true;
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
     * @return bool
     */
    public function setRoomID(?int $room_id): bool
    {
        $this->room_id = $room_id;
        return true;
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
            $this->room = \Repositories\Rooms::retrieve($this->room_id);
        }
        return $this->room;
    }

    /**
     * @param Room|null $r
     * @return bool
     */
    public function setRoom(?Room $r): bool
    {
        $this->room = $r;
        if ($r === null) {
            $this->room_id = null;
        } else {
            $this->room_id = $r->getID();
        }
        return true;
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
     * @return bool
     */
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
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

    public function __toString(): string
    {
        return sprintf("Périphérique \"%s\"<br/>
            UUID:\t\t%s<br/>
            Display Name:\t\t%s<br/>
            Build Date:\t\t%s<br/>
            Add Date:\t\t%s<br/>
            Public Key:\t\t%s<br/>
            Property ID: \t\t%s<br/>
            Room.php ID:\t\t%s<br/>
            Last Updated:\t\t%s<br/>",
            $this->getDisplayName(),
            $this->getUUID(),
            $this->getDisplayName(),
            $this->getBuildDate(),
            $this->getAddDate(),
            $this->getPublicKey(),
            $this->getPropertyID(),
            $this->getRoomID(),
            $this->getLastUpdated());
    }
}

function testPeripheralModel()
{
    // Create a new entity
    echo "<b>Création d'un nouveau Entities\Peripheral...</b>";
    $p1 = new Peripheral();
    echo "<b>Succès !</b>" . "<br/>";
    echo $p1;
    echo "<br/>";

    // Insert it
    echo "<b>Insertion de cet objet dans la BDD...</b>";
    Repositories\Peripherals::insert($p1);
    echo "<b>Succès !</b>" . "<br/>";
    echo $p1;

    // Change data
    $p1->setDisplayName("Périphérique de Test");
    $p1->setBuildDate("1997-04-09");

    // Push
    echo "<b>Push des dernières modifications...</b>";
    Repositories\Peripherals::push($p1);
    echo "<b>Succès !</b>" . "<br/>";

    // Pull
    echo "<b>Pull post-push...</b>";
    Repositories\Peripherals::pull($p1);
    echo "<b>Succès !</b>" . "<br/>";

    // Attach to property
    echo "<b>Attachement à la propriété...</b>";
    $p1->attachToProperty(1);
    echo "<b>Succès !</b>" . "<br/>";
    echo $p1;
    echo "<br/>";

    // Attach to room
    echo "<b>Attachement à la pièce...</b>";
    $p1->attachToRoom(1);
    echo "<b>Succès !</b>" . "<br/>";
    echo $p1;
    echo "<br/>";
}

testPeripheralModel();
