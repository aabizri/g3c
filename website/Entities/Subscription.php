<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 22/12/2017
 * Time: 14:41
 */

namespace Entities;


/**
 * Class Subscription
 * @package Entities
 */
class Subscription extends Entity
{
    /* Properties */

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $property_id;

    /**
     * @var string (YYYY-MM-DD)
     */
    private $start_date;

    /**
     * @var string (YYYY-MM-DD)
     */
    private $expiry_date;

    /**
     * @var int
     */
    private $command_id;

    /**
     * @var float
     */
    private $last_updated;

    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getID()
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
    public function getPropertyID(): int
    {
        return $this->property_id;
    }

    /**
     * @param int $property_id
     * @return bool
     */
    public function setPropertyID(int $property_id): bool
    {
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->start_date;
    }

    /**
     * @param string $start_date
     * @return bool
     */
    public function setStartDate(string $start_date): bool
    {
        $this->start_date = $start_date;
        return true;
    }

    /**
     * @return string
     */
    public function getExpiryDate(): string
    {
        return $this->expiry_date;
    }

    /**
     * @param string $expiry_date
     * @return bool
     */
    public function setExpiryDate(string $expiry_date): bool
    {
        $this->expiry_date = $expiry_date;
        return true;
    }

    /**
     * @return int
     */
    public function getCommandID(): ?int
    {
        return $this->command_id;
    }

    /**
     * @param int $command_id
     * @return bool
     */
    public function setCommandID(?int $command_id): bool
    {
        $this->command_id = $command_id;
        return true;
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
     * @return bool
     */
    public function setLastUpdated(float $last_updated)
    {
        $this->last_updated = $last_updated;
        return true;
    }
}