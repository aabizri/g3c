<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/01/2018
 * Time: 22:38
 */

namespace Entities;


/**
 * Class CGU
 * @package Entities
 */
class CGU extends Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

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
    public function setID(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return float
     */
    public function getLastUpdated(): float
    {
        return $this->last_updated;
    }

    /**
     * @param float $last_updated
     */
    public function setLastUpdated(float $last_updated): void
    {
        $this->last_updated = $last_updated;
    }
}