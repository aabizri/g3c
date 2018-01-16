<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 16/01/2018
 * Time: 10:11
 */

namespace Entities;


class Actuator extends Entity
{
    private $id;
    private $measure_type_id;
    private $last_action_started;
    private $peripheral_uuid;
    private $last_updated;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): ?int
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getMeasureTypeId()
    {
        return $this->measure_type_id;
    }

    /**
     * @param mixed $measure_type_id
     */
    public function setMeasureTypeId( int $measure_type_id): ?int
    {
        $this->measure_type_id = $measure_type_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getLastActionStarted()
    {
        return $this->last_action_started;
    }

    /**
     * @param mixed $last_action_started
     */
    public function setLastActionStarted( float $last_action_started): ?float
    {
        $this->last_action_started = $last_action_started;
        return true;
    }

    /**
     * @return mixed
     */
    public function getPeripheralUuid()
    {
        return $this->peripheral_uuid;
    }

    /**
     * @param mixed $peripheral_uuid
     */
    public function setPeripheralUuid( string $peripheral_uuid): ?string
    {
        $this->peripheral_uuid = $peripheral_uuid;
        return true;
    }

    /**
     * @return mixed
     */
    public function getLastUpdated()
    {
        return $this->last_updated;
    }

    /**
     * @param mixed $last_updated
     */
    public function setLastUpdated( float $last_updated): ?float
    {
        $this->last_updated = $last_updated;
        return true;
    }


}