<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/3/18
 * Time: 9:27 PM
 */

namespace Queries;


/**
 * Class Properties
 * @package Queries
 */
class Properties extends Query
{
    /* COMMON CONSTANTS */
    private const table = "properties";
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "name" => [],
                             "address" => [],
                             "creation_date" => ["timestamp"],
                             "last_updated" => ["timestamp"]];
    private const entity_class_name = "\Entities\Property";

    /**
     * Properties constructor.
     * Calls the parent's one.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct(self::table, self::columns, self::entity_class_name);
    }

    /* OTHERS */

    /**
     * @param \Entities\Property $property
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Property $property): bool
    {
        return parent::saveEntity($property);
    }
}