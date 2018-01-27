<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 8:21 PM
 */

namespace Entities;

use Queries;
/**
 * Class Room
 * @package Entities
 */
class Room extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $property_id;

    /**
     * @var Property
     */
    private $property;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $creation_date;

    /**
     * @var float
     */
    private $last_updated;

    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setID(int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getPropertyID(): ?int
    {
        return $this->property_id;
    }

    /**
     * @param int $property_id
     * @return bool
     */
    public function setPropertyID(int $property_id): bool
    {
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return Property
     */
    public function getProperty(): ?Property
    {
        if ($this->property === null) {
            $this->property = (new \Queries\Properties)->retrieve($this->property_id);
        }
        return $this->property;
    }

    /**
     * @param Property $p
     * @return bool
     */
    public function setProperty(Property $p): bool
    {
        $this->property = $p;
        $this->property_id = $p->getID();
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function setName(string $name): bool
    {
        $this->name = $name;
        return true;
    }

    /**
     * @return float
     */
    public function getCreationDate(): ?float
    {
        return $this->creation_date;
    }

    /**
     * @param float $creation_date
     * @return bool
     */
    public function setCreationDate(float $creation_date): bool
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if ($creation_date > microtime(true)) {
            return false;
        }

        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return float
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
     * @param int $property_id
     * @return bool
     */
    public function attachToProperty(int $property_id): bool
    {
        // Check if it exists
        // Use setPropertyId
        return false;
    }
}