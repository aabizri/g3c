<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/4/18
 * Time: 3:19 PM
 */

namespace Queries;


class MeasureTypes extends Query
{
    /* COMMON CONSTANTS */
    private const table = "measure_types";
    private const columns = ["id", "name", "description", "unit_name", "unit_symbol", "min", "max"];
    private const entity_class_name = "\Entities\MeasureType";

    /**
     * Rooms constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    public function save(\Entities\MeasureType $measure_type): bool
    {
        return parent::saveEntity($measure_type);
    }
}