<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 12/01/2018
 * Time: 16:33
 */

namespace Queries;


class Filters extends Query
{
    /* COMMON CONSTANTS */
    private const table = "filters";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "property_id" => [],
        "sensor_id" => [],
        "actuator_id" => [],
        "name" => [],
        "operator" => [],
        "threshold" => [],
        "actuator_params" => [],
        "creation_date" => [],
        "last_updated" => []];
    private const entity_class_name = "\Entities\Filter";

    /**
     * Measures constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }


}