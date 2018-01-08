<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/5/17
 * Time: 6:31 PM
 */

namespace Repositories;

class Permissions extends Repository
{
    /**
     * Find all permissions for the given role
     *
     * @param int $role_id the role id
     * @return int[] array of permission ids
     */
    public static function findAllByRole(int $role_id): array
    {
        // SQL
        $sql = "SELECT permission_id
            FROM roles_permissions
            WHERE role_id = :role_id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute([":role_id" => $role_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}