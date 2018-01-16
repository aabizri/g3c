<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 16/01/2018
 * Time: 10:16
 */

namespace Queries;


use Entities\Actuator;

class Actuators extends Query
{
    /* COMMON CONSTANTS */
    private const table = "actuators";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "measure_type_id" => [],
        "last_action_started" => [],
        "peripheral_uuid" => [],
        "last_updated" => []];
    private const entity_class_name = "\Entities\Actuator";

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

    /**
     * @param \Entities\Actuator $actuator
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Actuator $actuator): bool
    {
        return parent::saveEntity($actuator);
    }

    /**
     * @param string $peripheral_uuid
     * @param string $operator
     * @return Actuator
     */

    public function filterByPeripheralUUID(string $operator, string $peripheral_uuid): self
    {
        return $this -> filterByColumn("peripheral_uuid", $operator, $peripheral_uuid);
    }
}