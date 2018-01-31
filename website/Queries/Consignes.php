<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 15/01/2018
 * Time: 19:37
 */

namespace Queries;


class Consignes extends Query
{
    /* COMMON CONSTANTS */
    private const table = "consignes";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "actuator_id" => [],
                             "destination_value" => [],
                             "active" => [],
                             "creation_date" => ["timestamp"],
                             "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Consigne";

    /**
     * Consigne constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    /**
     * @param \Entities\Consigne $consigne
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Consigne $consigne): bool
    {
        return parent::saveEntity($consigne);
    }
}