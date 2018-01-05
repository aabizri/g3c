<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */

namespace Repositories;

use Entities;
use Exceptions\MultiSetFailedException;
use Exceptions\RowNotFoundException;
use Exceptions\SetFailedException;

class Rooms extends Repository
{
    /**
     * Insert a new room to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Room $r the Room to insert
     * @throws \Exception
     */
    public static function insert(Entities\Room $r): void
    {
        // SQL
        $sql = "INSERT INTO rooms (property_id, name)
        VALUES (:property_id, :name)";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $r->getMultiple([
            "property_id",
            "name",
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // Pull
        self::pull($r);
    }

    /**
     * Push an existing room to the database
     *
     * @param Entities\Room $r the room to push
     * @throws \Exception
     */
    public static function push(Entities\Room $r): void
    {
        // SQL
        $sql = "UPDATE rooms
        SET property_id = :property_id, name = :name
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $r->getMultiple([
            "id",
            "property_id",
            "name",
        ]);

        // Execute query
        $stmt->execute($data);

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
    public static function pull(Entities\Room $r): void
    {
        // SQL
        $sql = "SELECT property_id, name, creation_date, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM rooms
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $r->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Peripheral found");
        }

        // Store
        $ok = $r->setMultiple([
            "id" => $data["id"],
            "name" => $data["name"],
            "property_id" => $data["property_id"],
            "creation_date" => $data["creation_date"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException($r, $data);
        }
    }

    /**
     * Checks if the given rooms exists in the database
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM rooms
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
     * Retrieve a room from the database given its id
     *
     * @param int $id of the room to retrieve
     * @return Entities\Room|null , null if it not found
     * @throws \Exception
     */
    public static function retrieve(int $id): ?Entities\Room
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create a User entity
        $r = new Entities\Room();

        // Set the ID
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // Call Pull on it
        self::pull($r);

        // Return the user_id
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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["property_id" => $property_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}