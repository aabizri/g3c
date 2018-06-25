<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 16/01/2018
 * Time: 10:11
 */

namespace Entities;


/**
 * Class Actuator
 * @package Entities
 */
class Actuator extends Entity
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $num_code;
    /**
     * @var int
     */
    private $measure_type_id;
    /**
     * @var \Entities\MeasureType|null
     */
    private $measure_type;
    /**
     * @var float|null
     */
    private $last_action_started;
    /**
     * @var string
     */
    private $peripheral_uuid;
    /**
     * @var float
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
    }

    /**
     * @return int
     */
    public function getNumCode(): int
    {
        return $this->num_code;
    }

    /**
     * @param int $id
     */
    public function setNumCode(int $num_code)
    {
        $this->num_code = $num_code;
    }

    /**
     * @return int
     */
    public function getMeasureTypeID(): int
    {
        return $this->measure_type_id;
    }

    /**
     * @param int $type_id
     */
    public function setMeasureTypeID(int $type_id)
    {
        $this->measure_type_id = $type_id;
    }

    /**
     * @return MeasureType
     * @throws \Exception
     */
    public function getMeasureType(): MeasureType
    {
        if ($this->measure_type === null) {
            $this->measure_type = (new \Queries\MeasureTypes)->retrieve($this->measure_type_id);
        }
        return $this->measure_type;
    }

    /**
     * @param MeasureType $mt
     */
    public function setMeasureType(MeasureType $mt)
    {
        $this->measure_type = $mt;
        $this->measure_type_id = $mt->getID();
    }

    /**
     * @return float
     */
    public function getLastActionStarted(): ?float
    {
        return $this->last_action_started;
    }

    /**
     * @param float $last_action_started
     */
    public function setLastActionStarted(?float $last_action_started)
    {
        $this->last_action_started = $last_action_started;
    }

    /**
     * @return mixed
     */
    public function getPeripheralUUID(): string
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
     * @return mixed
     */
    public function getLastUpdated(): float
    {
        return $this->last_updated;
    }

    /**
     * @param mixed $last_updated
     */
    public function setLastUpdated(float $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }

}