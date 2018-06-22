<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 6:03 PM
 */

namespace Entities;


/**
 * Class MeasureType
 * @package Entities
 */
class MeasureType extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $typ_code;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $unit_name;

    /**
     * @var string
     */
    private $unit_symbol;

    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /* GETTERS AND SETTERS */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTypCode(): int
    {
        return $this->id;
    }

    /**
     * @param int $typ_code
     */
    public function setTypCode(int $typ_code): void
    {
        $this->typ_code = $typ_code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUnitName(): string
    {
        return $this->unit_name;
    }

    /**
     * @param string $unit_name
     */
    public function setUnitName(string $unit_name): void
    {
        $this->unit_name = $unit_name;
    }

    /**
     * @return string
     */
    public function getUnitSymbol(): string
    {
        return $this->unit_symbol;
    }

    /**
     * @param string $unit_symbol
     */
    public function setUnitSymbol(string $unit_symbol): void
    {
        $this->unit_symbol = $unit_symbol;
    }

    /**
     * @return float
     */
    public function getMin(): float
    {
        return $this->min;
    }

    /**
     * @param float $min
     */
    public function setMin(float $min): void
    {
        $this->min = $min;
    }

    /**
     * @return float
     */
    public function getMax(): float
    {
        return $this->max;
    }

    /**
     * @param float $max
     */
    public function setMax(float $max): void
    {
        $this->max = $max;
    }
}