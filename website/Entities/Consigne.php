<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 15/01/2018
 * Time: 19:33
 */

namespace Entities;


class Consigne extends Entity
{
    private $id;
    private $actuator_id;
    private $destination_value;
    private $active;
    private $creation_date;
    private $last_updated;

    /**
     * @return mixed
     */
    public function getID() : int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setID( int $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getActuatorID() : int
    {
        return $this->actuator_id;
    }

    /**
     * @param mixed $actuator_id
     */
    public function setActuatorID( int $actuator_id): bool
    {
        $this->actuator_id = $actuator_id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getDestinationValue() : int
    {
        return $this->destination_value;
    }

    /**
     * @param mixed $destination_value
     */
    public function setDestinationValue( float $destination_value): bool
    {
        $this->destination_value = $destination_value;
        return true;
    }

    /**
     * @return mixed
     */
    public function getActive() : int
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive( bool $active): ?bool
    {
        $this->active = $active;
        return true;
    }

    /**
     * @return mixed
     */
    public function getCreationDate() : float
    {
        return $this->creation_date;
    }

    /**
     * @param mixed $creation_date
     */
    public function setCreationDate( float $creation_date): ?bool
    {
        $this->creation_date = $creation_date;
        return true;
    }

    /**
     * @return mixed
     */
    public function getLastUpdated() : float
    {
        return $this->last_updated;
    }

    /**
     * @param mixed $last_updated
     */
    public function setLastUpdated( float $last_updated): ?bool
    {
        $this->last_updated = $last_updated;
        return true;
    }


}