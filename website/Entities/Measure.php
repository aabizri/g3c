<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/17/17
 * Time: 1:54 PM
 */

namespace Entities;


class Measure
{
    /* PROPERTIES */

    private $id;
    private $type;
    private $date_time;
    private $value;
    private $peripheral_uuid;
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
     * @return bool
     */
    public function setID(int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function setType(string $type): bool
    {
        $this->type = $type;
        return true;
    }

    /**
     * @return string
     */
    public function getDateTime(): string
    {
        return $this->date_time;
    }

    /**
     * @param string $date_time
     * @return bool
     */
    public function setDateTime(string $date_time): bool
    {
        $this->date_time = $date_time;
        return true;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return bool
     */
    public function setValue(int $value): bool
    {
        $this->value = $value;
        return true;
    }

    /**
     * @return string
     */
    public function getPeripheralUUID(): string
    {
        return $this->peripheral_uuid;
    }

    /**
     * @param string $peripheral_uuid
     * @return bool
     */
    public function setPeripheralUUID(string $peripheral_uuid): bool
    {
        $this->peripheral_uuid = $peripheral_uuid;
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
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }
}