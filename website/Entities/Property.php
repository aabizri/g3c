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
     * @param float $creation_date
     * @throws \Exceptions\SetFailedException
     */
    public function setCreationDate(float $creation_date): void
    {
        // Verifier que $creation_date est inférieure à la date actuelle
        if (!self::validateCreationDate($creation_date)) {
            throw new \Exceptions\SetFailedException($this, __FUNCTION__, $creation_date);
        }
        $this->creation_date = $creation_date;
    }

    /**
     * @param float $creation_date
     * @return bool
     */
    public static function validateCreationDate(float $creation_date): bool
    {
        return parent::validateMicroTimeHasPassed($creation_date);
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
     * @throws \Exceptions\SetFailedException
     */
    public function setLastUpdated(float $last_updated): void
    {
        if (!self::validateLastUpdated($last_updated)) throw new \Exceptions\SetFailedException($this, __FUNCTION__, $last_updated);
        $this->last_updated = $last_updated;
    }

    /**
     * @param float $last_updated
     * @return bool
     */
    public static function validateLastUpdated(float $last_updated): bool
    {
        return parent::validateMicroTimeHasPassed($last_updated);
    }
}



