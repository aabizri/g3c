<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 22/12/2017
 * Time: 14:41
 */

namespace Entities;


class Subscription
{
    /* Properties */

    private $id;
    private $property_id;
    private $start_date;
    private $expiry_date;
    private $command_id;
    private $last_updated;



    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setId(int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getPropertyId(): int
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
    public function getCommandId(): int
    {
        return $this->command_id;
    }

    /**
     * @param int $command_id
     * @return bool
     */
    public function setCommandId(int $command_id): bool
    {
        $this->command_id = $command_id;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated(): string
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated($last_updated)
    {
        $this->last_updated = $last_updated;
        return true;
    }



}