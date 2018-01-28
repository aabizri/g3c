<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 11/01/2018
 * Time: 08:58
 */

namespace Entities;

use Helpers\UUID;
use Repositories;

class Sensor extends Entity
{

    /* SENSOR */

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $measure_type_id;


    /**
     * @var string
     */
    private $peripheral_uuid;

    /**
     * @var string
     */
    private $last_updated;

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setID(int $id)
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return int
     */
    public function getMeasureTypeID(): int
    {
        return $this->measure_type_id;
    }

    /**
     * @param int $measure_type_id
     */
    public function setMeasureTypeID(int $measure_type_id)
    {
        $this->measure_type_id = $measure_type_id;
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
     */
    public function setPeripheralUUID(string $peripheral_uuid)
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
     * @param string $last_updated
     */
    public function setLastUpdated(string $last_updated)
    {
        $this->last_updated = $last_updated;
        return true;
    }



}