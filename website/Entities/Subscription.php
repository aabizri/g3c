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
        $this->property_id = $property_id;

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
     *
     */
    public function setStartDate(string $start_date): void
    {
        $this->start_date = $start_date;

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
     *
     */
    public function setExpiryDate(string $expiry_date): void
    {
        $this->expiry_date = $expiry_date;

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
     *
     */
    public function setCommandID(?int $command_id): void
    {
        $this->command_id = $command_id;

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
     */
    public function setLastUpdated(float $last_updated): void
    {
        $this->last_updated = $last_updated;
    }
}