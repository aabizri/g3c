<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 25/01/2018
 * Time: 15:27
 */

namespace Queries;


use Queries\Query;

class Sensors extends Query
{
    /* COMMON CONSTANTS */
    private const table = "sensors";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "measure_type_id" => [],
        "peripheral_uuid" => [],
        "last_updated" => ["gen-on-insert", "timestamp"]];
    private const entity_class_name = "\Entities\Sensor";

    /**
     * Users constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }
}