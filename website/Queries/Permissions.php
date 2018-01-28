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
    private const columns = ["id" => ["id", "gen-on-insert"],
                             "name" => [],
                             "description" => [],
                             "creation_date" => ["gen-on-insert", "timestamp"],
                             "last_updated" => ["gen-on-insert", "timestamp"]];
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
     * Finds all permissions for role
     */
    public function filterByRoleViaPivot(int $role_id): self
    {
        // SQL
        $sql = "SELECT permission_id
            FROM roles_permissions
            WHERE role_id = :role_id";

        // Prepare statement
        $stmt = \Helpers\DB::getInstance()->prepare($sql, \Helpers\DB::$pdo_params);

        // Execute statement
        $stmt->execute([":role_id" => $role_id]);

        // Fetch all results
        $permission_ids = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Execute a big query
        foreach ($permission_ids as $id) {
            $this->filterByColumn("id", "=", $id);
        }

        // Return the set
        return $this;
    }

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