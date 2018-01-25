<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 25/01/2018
 * Time: 15:24
 */

namespace Entities;


class Sensor extends Entity
{
    private $id;
    private $measure_type_id;
    private $peripheral_uuid;
    private $last_updated;

    /**
     * @return mixed
     */
    public function getID() : ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setID( ?int $id): bool
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
    public function setMeasureTypeID( ?int $measure_type_id): bool
    {
        $this->measure_type_id = $measure_type_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getPeripheralUUID() : ?string
    {
        return $this->peripheral_uuid;
    }

    /**
     * @param mixed $peripheral_uuid
     */
    public function setPeripheralUUID( ?string $peripheral_uuid): bool
    {
        $this->peripheral_uuid = $peripheral_uuid;
        return true;
    }

    /**
     * @return mixed
     */
    public function getLastUpdated() : ?float
    {
        return $this->last_updated;
    }

    /**
     * @param mixed $last_updated
     */
    public function setLastUpdated( ?float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }


}