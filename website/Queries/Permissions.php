<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 1/4/18
 * Time: 3:19 PM
 */

namespace Queries;


/**
 * Class Permissions
 * @package Queries
 */
class Permissions extends Query
{
    /* COMMON CONSTANTS */
    private const table = "permissions";
    private const columns = ["id" => "",
                             "name" => "",
                             "description" => "",
                             "creation_date" => "timestamp",
                             "last_updated" => "timestamp"];
    private const entity_class_name = "\Entities\Permission";

    /**
     * Permissions constructor.
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
     * @param \Entities\Permission $permission
     * @return bool
     * @throws \Exception
     */
    public function save(\Entities\Permission $permission): bool
    {
        return parent::saveEntity($permission);
    }
}