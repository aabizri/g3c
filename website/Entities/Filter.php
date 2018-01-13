<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/01/2018
 * Time: 16:16
 */

namespace Entities;


class Filter extends Entity
{
    private $id;
    private $property_id;
    private $sensor_id;
    private $actuator_id;
    private $name;
    private $operator;
    private $threshold;
    private $actuator_params;
    private $creation_date;
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
    public function setId($id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getPropertyId()
    {
        return $this->property_id;
    }

    /**
     * @param mixed $property_id
     */
    public function setPropertyId($property_id): bool
    {
        $this->property_id = $property_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getSensorId()
    {
        return $this->sensor_id;
    }

    /**
     * @param mixed $sensor_id
     */
    public function setSensorId($sensor_id): bool
    {
        $this->sensor_id = $sensor_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getActuatorId()
    {
        return $this->actuator_id;
    }

    /**
     * @param mixed $actuator_id
     */
    public function setActuatorId($actuator_id): bool
    {
        $this->actuator_id = $actuator_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): bool
    {
        $this->name = $name;
        return true;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator): bool
    {
        $this->operator = $operator;
        return true;
    }

    /**
     * @return mixed
     */
    public function getThreshold()
    {
        return $this->threshold;
    }

    /**
     * @param mixed $threshold
     */
    public function setThreshold($threshold): bool
    {
        $this->threshold = $threshold;
        return true;
    }

    /**
     * @return mixed
     */
    public function getActuatorParams()
    {
        return $this->actuator_params;
    }

    /**
     * @param mixed $actuator_params
     */
    public function setActuatorParams($actuator_params): bool
    {
        $this->actuator_params = $actuator_params;
        return true;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param mixed $creation_date
     */
    public function setCreationDate($creation_date): bool
    {
        $this->creation_date = $creation_date;
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
    public function setLastUpdated($last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }


}