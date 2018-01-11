<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/01/2018
 * Time: 22:38
 */

namespace Entities;


class CGU extends Entity
{
    private $id;
    private $text;
    private $last_updated;

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setID(int $id)
    {
        $this->id = $id;
        return true;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
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
    public function setLastUpdated(float $last_updated)
    {
        $this->last_updated = $last_updated;
        return true;
    }


}