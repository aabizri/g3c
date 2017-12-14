<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 12/12/2017
 * Time: 10:22
 */

namespace Entities;


class Sensor
{

    /*SENSORS */

    private $id;
    private $sense_type;
    private $last_measure;
    private $last_updated;


    /** SETTERS AND GETTERS **/

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setId(int $id):bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return string
     */
    public function getSenseType():string
    {
        return $this->sense_type;
    }

    /**
     * @param string $sense_type
     * @return bool
     */
    public function setSenseType(string $sense_type):bool
    {
        $this->sense_type = $sense_type;
        return true;
    }

    /**
     * @return int
     */
    public function getLastMeasure():int
    {
        return $this->last_measure;
    }

    /**
     * @param int $last_measure
     * @return bool
     */
    public function setLastMeasure(int $last_measure):bool
    {
        $this->last_measure = $last_measure;
        return true;
    }

    /**
     * @return string
     */
    public function getLastUpdated():string
    {
        return $this->last_updated;
    }

    /**
     * @param string $last_updated
     * @return bool
     */
    public function setLastUpdated(string $last_updated): bool
    {
        $this->last_updated = $last_updated;
        return true;
    }


}