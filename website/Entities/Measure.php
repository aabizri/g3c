<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/17/17
 * Time: 1:54 PM
 */

namespace Entities;

/**
 * Class Measure
 * @package Entities
 * @todo actuator & sensor getters and setters
 */
class Measure extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type_id;

    /**
     * @var MeasureType
     */
    private $type;

    /**
     * @var string date_time
     */
    private $date_time;

    /**
     * @var double
     */
    private $value;

    /**
     * @var int
     */
    private $actuator_id;

    /**
     * @var int
     */
    private $sensor_id;


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
    public function getTypeID(): string
    {
        return $this->type_id;
    }

    /**
     * @param string $type_id
     * @return bool
     */
    public function setTypeID(string $type_id): bool
    {
        $this->type_id = $type_id;
        return true;
    }

    /**
     * @return MeasureType
     * @throws \Exception
     */
    public function getType(): MeasureType
    {
        if ($this->type === null) {
            $this->type = (new \Queries\MeasureTypes)->retrieve($this->type_id);
        }
        return $this->type;
    }

    /**
     * @param MeasureType $mt
     * @return bool
     */
    public function setType(MeasureType $mt): bool
    {
        $this->type = $mt;
        $this->type_id = $mt->getID();
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
     * @return double
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param double $value
     * @return bool
     */
    public function setValue(double $value): bool
    {
        $this->value = $value;
        return true;
    }

    /**
     * @return int|null
     */
    public function getActuatorID(): ?int
    {
        return $this->actuator_id;
    }

    /**
     * @param int $actuator_id
     * @return bool
     */
    public function setActuatorID(int $actuator_id): bool
    {
        $this->actuator_id = $actuator_id;
        return true;
    }

    /**
     * @return int|null
     */
    public function getSensorID(): ?int
    {
        return $this->sensor_id;
    }

    /**
     * @param int $sensor_id
     * @return bool
     */
    public function setSensorID(int $sensor_id): bool
    {
        $this->sensor_id = $sensor_id;
        return true;
    }
}