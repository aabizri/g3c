<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 8:21 PM
 */

namespace Entities;

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
    public function getID(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     */
    public function setID(int $id): void
    {
        $this->id = $id;

    }

    /**
     * @return int
     */
    public function getPropertyID(): int
    {
        return $this->property_id;
    }

    /**
     * @param int $property_id
     *
     */
    public function setPropertyID(int $property_id): void
    {
        if ($this->property !== null) {
            if ($property_id !== $this->property->getID()) {
                $this->property = null;
            }
        }
        $this->property_id = $property_id;

    }

    /**
     * @return Property
     */
    public function getProperty(): Property
    {
        if ($this->property === null) {
            $this->property = (new \Queries\Properties)->retrieve($this->property_id);
        }
        return $this->property;
    }

    /**
     * @param Property $p
     *
     */
    public function setProperty(Property $p): void
    {
        $this->property = $p;
        $this->property_id = $p->getID();

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
     *
     */
    public function setName(string $name): void
    {
        $this->name = $name;

    }

    /**
     * @return float
     */
    public function getCreationDate(): float
    {
        return $this->creation_date;
    }

    /**
     * @param float $creation_date
     *
     */
    public function setCreationDate(float $creation_date): void
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if ($creation_date > microtime(true)) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $creation_date, "creation_date sooner than right now");
        }

        $this->creation_date = $creation_date;

    }

    /**
     * @return float
     */
    public function getLastUpdated(): float
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
}