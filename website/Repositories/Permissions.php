<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/5/17
 * Time: 6:31 PM
 */

namespace Repositories;


use Exceptions\RowNotFoundException;
use Exceptions\MultiSetFailedException;
use Exceptions\SetFailedException;

class Permissions extends Repository
{
    /**
     * insert adds an entity to the database
     *
     * @param \Entities\Permission $p
     * @throws \Exception
     */
    public static function insert(\Entities\Permission $p): void
    {
        // SQL
        $sql = "INSERT INTO permissions (name, description)
          VALUES (:name, :description);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $p->getMultiple([
            "name",
            "description",
        ]);

        // Execute request
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $p->setID($id);
        if (!$ok) {
            throw new SetFailedException($p,"setID",$id);
        }

        // We should now pull to populate times
        self::pull($p);
    }

    /**
     * Push an existing permission to the database
     *
     * @param \Entities\Permission $p the user_id to push
     *
     * @throws \Exception
     */
    public static function push(\Entities\Permission $p): void
    {
        // SQL
        $sql = "UPDATE permissions
        SET name = :name, description = :description";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $p->getMultiple([
            'name',
            'description',
        ]);

        // Execute query
        $stmt->execute($data);

        // Now pull
        self::pull($p);
    }

    /**
     * Pull an existing permission from the database
     *
     * @param \Entities\Permission $p the permission to pull
     *
     * @return void
     *
     * @throws \Exception
     */
    public static function pull(\Entities\Permission $p)
    {
        // SQL
        $sql = "SELECT name, description
        FROM permissions
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $p->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            throw new RowNotFoundException($p, "permissions");
        }

        // Store
        $ok = $p->setMultiple($data);
        if (!$ok) {
            throw new MultiSetFailedException($p,$data);
        }
    }

    /**
     * Retrieve a permission from the database given its id
     *
     * @param int $id of the permission to retrieve
     * @return \Entities\Permission the room if found, null if not
     * @throws \Exception
     */
    public static function retrieve(int $id): \Entities\Permission
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM permissions
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a User entity
        $p = new \Entities\Permission();

        // Set the ID
        $ok = $p->setID($id);
        if (!$ok) {
            throw new SetFailedException($p,"setID",$id);
        }

        // Call Pull on it
        self::pull($p);

        // Return the user_id
        return $p;
    }

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