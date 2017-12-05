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
     * @param \Entities\Property $p
     * @throws \Exception
     */
    public static function insert(\Entities\Role $p): void
    {
        // SQL
        $sql = "INSERT INTO roles (user_id, property_id, perms)
          VALUES (:user_id, :property_id, :perms);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            ":user_id" => $p->getUserId(),
            ":property_id" => $p->getPropertyId(),
            ":perms" => $p->getPerms()
        ];

        // Execute request
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($p->setId($id) == false) {
            throw new \Exception("error setting id");
        }

        // We should now pull to populate ID & Times
        self::pull($p);
    }
}