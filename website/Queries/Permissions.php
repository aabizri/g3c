<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/4/18
 * Time: 3:19 PM
 */

namespace Queries;


class Permissions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "permissions";
    private const columns = ["id" => "",
                             "name" => "",
                             "description" => "",
                             "creation_date" => "",
                             "last_updated" => ""];
    private const entity_class_name = "\Entities\Permission";

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

    public function save(\Entities\Permission $permission): bool
    {
        return parent::saveEntity($permission);
    }
}