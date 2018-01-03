<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 10:20 PM
 */

namespace Queries\Clauses;


class Where
{
    /**
     * Les triplets (Colonne - Opérateur - Indicateur) ou les Conditions (permettant ainsi la récursivité)
     * @var WhereTriplet[]|Where[]
     */
    public $operands;

    /**
     * Le type de lien entre eux (AND, OR, etc)
     * @var string
     */
    public $operator;

    public function __construct(string $operator, ...$operands)
    {
        $this->operands = $operands;
        $this->operator = $operator;
    }

    public function toSQL(): string
    {
        $out = "( ";
        foreach ($this->operands as $index => $operand) {
            $out .= $operand->toSQL();
            if ($index !== (count($this->operands) - 1)) {
                $out .= $this->operator;
            }
        }
        $out .= " )";
        return $out;
    }
}