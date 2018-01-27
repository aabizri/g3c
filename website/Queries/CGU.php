<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/01/2018
 * Time: 22:45
 */

namespace Queries;


class CGU extends Query
{
    /* COMMON CONSTANTS */
    private const table = "cgu";
    private const columns = ["id" => ["id", "gen-on-insert"],
        "text" => [],
        "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\CGU";

    /**
     * CGU constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    /**
     * @param \Entities\CGU $cgu
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\CGU $cgu): bool
    {
        return parent::saveEntity($cgu);
    }


}