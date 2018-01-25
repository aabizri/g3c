<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 13/01/2018
 * Time: 15:53
 */

namespace Entities;


class Sensor extends Entity
{
    /*SENSORS */

    private $id;
    private $measure_type_id;
    private $peripheral_uuid;
    private $last_updated;

    /** SETTERS AND GETTERS **/

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
    public function setID(int $id):bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getMeasureTypeID() : ?int
    {
        return $this->measure_type_id;
    }

    /**
     * @param mixed $measure_type_id
     */
    public function setMeasureTypeID(int $measure_type_id): bool
    {
        $this->measure_type_id = $measure_type_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getPeripheralUUID(): ?string
    {
        return $this->peripheral_uuid;
    }

    /**
     * @param mixed $peripheral_uuid
     */
    public function setPeripheralUUID(string $peripheral_uuid): bool
    {
        $this->peripheral_uuid = $peripheral_uuid;
        return true;
    }


    /**
     * @return string
     */
    public function getLastUpdated(): ?float
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }
}