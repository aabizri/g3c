<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 27/11/2017
 * Time: 11:46
 */

namespace Entities;

/**
 * Class Property
 * @package Entities
 */
class Property extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var float
     */
    private $creation_date;

    /**
     * @var float
     */
    private $last_updated;

    /* SETTERS AND GETTERS */

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
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;

    }

    /**
     * @return float
     */
    public function getCreationDate(): float
    {
        return $this->creation_date;
    }

    /**
     * @param flaot $creation_date
     *
     */
    public function setCreationDate(float $creation_date): void
    {
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



