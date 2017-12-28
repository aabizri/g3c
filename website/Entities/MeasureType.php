<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 6:03 PM
 */

namespace Entities;


class MeasureType extends Entity
{
    /* PROPERTIES */

    /**
     * @var int
     */
    private $id;

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
     * @var double
     */
    private $min;

    /**
     * @var double
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
     * @return double
     */
    public function getMin(): double
    {
        return $this->min;
    }

    /**
     * @param double $min
     */
    public function setMin(double $min): void
    {
        $this->min = $min;
    }

    /**
     * @return double
     */
    public function getMax(): double
    {
        return $this->max;
    }

    /**
     * @param double $max
     */
    public function setMax(double $max): void
    {
        $this->max = $max;
    }
}