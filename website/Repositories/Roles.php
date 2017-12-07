<?php

namespace Repositories;


use Entities;
use Exception;
use PDO;

class Roles extends Repository
{
    /**
     * insert adds an entity to the database
     *
     * @param \Entities\Role $p
     * @throws \Exception
     */
    public static function insert(\Entities\Role $p): void
    {
        // SQL
        $sql = "INSERT INTO roles (user_id, property_id)
          VALUES (:user_id, :property_id);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            ":user_id" => $p->getUserID(),
            ":property_id" => $p->getPropertyID(),
        ];

        // Execute request
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($p->setId($id) == false) {
            throw new \Exception("error setting id");
        }

        // We should now pull to populate times
        self::pull($p);
    }

    /**
     * Push an existing role to the database
     *
     * @param Entities\Role $r the user_id to push
     *
     * @throws Exception if the subsequent pull fails
     */
    public static function push(Entities\Role $r): void
    {
        // SQL
        $sql = "UPDATE roles
        SET user_id = :uid, property_id = :pid";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = array(
            ':uid' => $r->getUserID(),
            ':pid' => $r->getPropertyID(),
        );

        // Execute query
        $sth->execute($data);

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
        $sql = "SELECT user_id, property_id, creation_date, last_updated
        FROM roles
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $r->getID()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            throw new Exception("no data returned");
        }

        // Store
        $arr = array(
            "setUserId" => $data["user_id"],
            "setPropertyId" => $data["property_id"],
            "setCreationDate" => $data["creation_date"],
            "setLastUpdated" => $data["last_updated"],
        );
        parent::executeSetterArray($r, $arr);
    }

    /**
     * Retrieve a role from the database given its id
     *
     * @param int $id of the room to retrieve
     * @return Entities\Role the room if found, null if not
     * @throws Exception
     */
    public static function retrieve(int $id): Entities\Role
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM roles
            WHERE id = :id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':id' => $id));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a User entity
        $r = new Entities\Role();

        // Set the ID
        $r->setId($id);

        // Call Pull on it
        self::pull($r);

        // Return the user_id
        return $r;
    }

    /**
     * Find all roles for this user_id
     *
     * @param int $uid user_id id
     * @return int[] array of role ids
     */
    public static function findAllByUserID(int $uid): array {
        // SQL
        $sql = "SELECT id
            FROM roles
            WHERE user_id = :uid";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":uid" => $uid]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find all roles for this property
     *
     * @param int $pid property id
     * @return int[] array of role ids
     */
    public static function findAllByPropertyID(int $pid): array {
        // SQL
        $sql = "SELECT id
            FROM roles
            WHERE property_id = :pid";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":pid" => $pid]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find all roles with this permission ID
     *
     * @param int $permission_id permission ID
     * @return int[] array of role ids
     */
    public static function findAllByPermissionID(int $permission_id): array {
        // SQL
        $sql = "SELECT role_id
            FROM roles_permissions
            WHERE permission_id = :permission_id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":pid" => $permission_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Find the role matching a property and a user_id
     *
     * @param int $uid user_id id
     * @param int $pid property id
     * @return int role id
     * @throws Exception
     */
    public static function findByUserAndProperty(int $uid, int $pid): int {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM roles
            WHERE user_id = :uid AND property_id = :pid";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare parameters
        $params = [':uid' => $uid, ":pid" => $pid];

        // Execute query
        $sth->execute($params);

        // Fetch
        $count = $sth->fetchColumn(0);

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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute($params);

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }
}