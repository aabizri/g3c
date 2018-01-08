<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 10:17 PM
 */

namespace Queries\Clauses;


class WhereTriplet
{
    // La colonne
    public $column;

    // L'opérateur
    public $operator;

    // L'indicateur subséquemment utilisé
    public $indicator;

    public function __construct(string $column, string $operator, string $indicator)
    {
        $this->column = $column;
        $this->operator = $operator;
        $this->indicator = $indicator;
    }

    public function toSQL(): string
    {
        return $this->column . " " . $this->operator . " :" . $this->indicator;
    }
}