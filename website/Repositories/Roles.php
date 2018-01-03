<?php

namespace Repositories;


use Entities;
use Exception;
use PDO;
use Exceptions\MultiSetFailedException;
use Exceptions\RowNotFoundException;
use Exceptions\SetFailedException;

class Roles extends Repository
{
    /**
     * insert adds an entity to the database
     *
     * @param \Entities\Role $r
     * @throws \Exception
     */
    public static function insert(\Entities\Role $r): void
    {
        // SQL
        $sql = "INSERT INTO roles (user_id, property_id)
          VALUES (:user_id, :property_id);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $r->getMultiple([
            "user_id",
            "property_id",
        ]);

        // Execute request
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // We should now pull to populate times
        self::pull($r);
    }

    /**
     * Push an existing role to the database
     *
     * @param Entities\Role $r the role to push
     *
     * @throws Exception if the subsequent pull fails
     */
    public static function push(Entities\Role $r): void
    {
        // SQL
        $sql = "UPDATE roles
        SET user_id = :user_id, property_id = :property_id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $r->getMultiple([
            'user_id',
            'property_id'
        ]);

        // Execute query
        $stmt->execute($data);

        // Now pull
        self::pull($r);
    }

    /**
     * Pull an existing role from the database
     *
     * @param Entities\Role $r the peripheral to pull
     *
     * @return void
     *
     * @throws Exception if there is no such Model\Peripheral
     */
    public static function pull(Entities\Role $r)
    {
        // SQL
        $sql = "SELECT user_id, property_id, UNIX_TIMESTAMP(creation_date) AS creation_date, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM roles
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $r->getID()]);

        // Retrieve
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            new RowNotFoundException($r,"roles");
        }

        // Store
        $ok = $r->setMultiple([
            "user_id" => $data["user_id"],
            "property_id" => $data["property_id"],
            "creation_date" => (float)$data["creation_date"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if (!$ok) {
            throw new MultiSetFailedException($r,$data);
        }
    }

    /**
     * Checks if the given role exists in the database
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM roles
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    /**
     * Retrieve a role from the database given its id
     *
     * @param int $id of the room to retrieve
     * @return Entities\Role|null the room if found, null if not
     * @throws Exception
     */
    public static function retrieve(int $id): ?Entities\Role
    {
        // If it doesn't exists, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create a User entity
        $r = new Entities\Role();

        // Set the ID
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // Call Pull on it
        self::pull($r);

        // Return the entity
        return $r;
    }

    /**
     * Find all roles for this user
     *
     * @param int $uid the id of the user
     * @return int[] array of role ids
     */
    public static function findAllByUserID(int $uid): array
    {
        // SQL
        $sql = "SELECT id
            FROM roles
            WHERE user_id = :uid";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["uid" => $uid]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find all roles for this property
     *
     * @param int $pid property id
     * @return int[] array of role ids
     */
    public static function findAllByPropertyID(int $pid): array
    {
        // SQL
        $sql = "SELECT id
            FROM roles
            WHERE property_id = :pid";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["pid" => $pid]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find all roles with this permission ID
     *
     * @param int $permission_id permission ID
     * @return int[] array of role ids
     */
    public static function findAllByPermissionID(int $permission_id): array
    {
        // SQL
        $sql = "SELECT role_id
            FROM roles_permissions
            WHERE permission_id = :permission_id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["pid" => $permission_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find the role matching a property and a user_id
     *
     * @param int $uid user_id id
     * @param int $pid property id
     * @return int|null role id or null if not found
     * @throws Exception
     */
    public static function findByUserAndProperty(int $uid, int $pid): ?int
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM roles
            WHERE user_id = :uid AND property_id = :pid";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare parameters
        $params = [':uid' => $uid, ":pid" => $pid];

        // Execute query
        $stmt->execute($params);

        // Fetch
        $count = $stmt->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one role matches !");
        }

        // SQL for selecting
        $sql = "SELECT id
            FROM roles
            WHERE user_id = :uid AND property_id = :pid";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute($params);

        // Fetch
        $id = $stmt->fetchColumn(0);

        // Return this ID
        return $id;
    }

    public static function delete(int $id){

        //sql pour supprimer
        $sql = "DELETE FROM roles WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute request
        $stmt->execute(["id" => $id]);

    }
}