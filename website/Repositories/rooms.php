<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */

namespace Repositories;

use Entities;

class Rooms extends Repository
{
    /**
     * Insert a new room to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Room $r the Room to insert
     */
    public static function insert(Entities\Room $r): void
    {
        // SQL
        $sql = "INSERT INTO rooms (id, property_id, name)
        VALUES (:id, :property_id, :name)";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            "id" => $r->getId(),
            "property_id" => $r->getPropertyId(),
            "name" => $r->getName(),
        ];

        // Execute query
        $sth->execute($data);

        // Pull
        self::pull($r);
    }

    /**
     * Push an existing room to the database
     *
     * @param Entities\Room $r the room to push
     */
    public static function push(Entities\Room $r): void
    {
        // SQL
        $sql = "UPDATE rooms
        SET property_id = :property_id, name = :name
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = [
            "id" => $r->getId(),
            "property_id" => $r->getPropertyId(),
            "name" => $r->getName(),
        ];

        // Execute query
        $sth->execute($data);

        // Pull
        self::pull($r);
    }

    /**
     * Pull an existing room from the database
     *
     * @param Entities\Room $r the room to pull
     *
     * @return void
     *
     * @throws \Exception if there is no such Model\Peripheral
     */
    public static function pull(Entities\Room $r)
    {
        // SQL
        $sql = "SELECT property_id, name, creation_date, last_updated
        FROM rooms
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $r->getID()));

        // Retrieve
        $data = $sth->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Peripheral found");
        }

        // Store
        $arr = array(
            "setId" => $data["id"],
            "setPropertyId" => $data["property_id"],
            "setCreationDate" => $data["creation_date"],
            "setLastUpdated" => $data["last_updated"],
        );
        parent::executeSetterArray($r, $arr);
    }

    /**
     * Retrieve a room from the database given its id
     *
     * @param int $id of the room to retrieve
     * @return Entities\Room the room if found, null if not
     * @throws \Exception
     */
    public static function retrieve(int $id): Entities\Room
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM rooms
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
        $r = new Entities\Room();

        // Set the ID
        $r->setId($id);

        // Call Pull on it
        self::pull($r);

        // Return the user
        return $r;
    }

    /**
     * findAllByPropertyID retrieves all IDs for rooms belonging to that property
     *
     * @param int $property_id
     * @return int[] array of room ids
     */
    public static function findAllByPropertyID(int $property_id): array
    {
        // SQL
        $sql = "SELECT id
            FROM rooms
            WHERE property_id = :property_id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":property_id" => $property_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}