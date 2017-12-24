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
     * @throws \Exception
     */
    public static function insert(Entities\Room $r): void
    {
        // SQL
        $sql = "INSERT INTO rooms (property_id, name)
        VALUES (:property_id, :name)";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $r->getMultiple([
            "property_id",
            "name",
        ]);

        // Execute query
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($r->setID($id) == false) {
            throw new \Exception("error setting id");
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $r->getMultiple([
            "id",
            "property_id",
            "name",
        ]);

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
    public static function pull(Entities\Room $r): void
    {
        // SQL
        $sql = "SELECT property_id, name, creation_date, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM rooms
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(['id' => $r->getID()]);

        // Retrieve
        $data = $sth->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Peripheral found");
        }

        // Store
        $ok = $r->setMultiple([
            "id" => $data["id"],
            "property_id" => $data["property_id"],
            "creation_date" => $data["creation_date"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new \Exception("Error setting data");
        }
    }

    /**
     * Syncs a room with the database, executing a Pull or a Push on a last_updated timestamp basis
     *
     * @param Entities\Room $r to be synced
     *
     * @return void
     *
     * @throws \Exception if not found
     */
    public static function sync(Entities\Room $r): void
    {
        // SQL to get last_updated on given peripheral
        $sql = "SELECT UNIX_TIMESTAMP(last_updated) AS last_updated
          FROM rooms
          WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(['id' => $r->getID()]);

        // Retrieve
        $db_last_updated = $sth->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated == null) {
            throw new \Exception("No such Room found");
        }

        // If empty, that's an Exception
        if ($db_last_updated == "") {
            throw new \Exception("Empty last_updated");
        }

        // Cast it
        $db_last_updated = (float)$db_last_updated;

        // If the DB was updated BEFORE the last update to the peripheral, push
        if ($db_last_updated < $r->getLastUpdated()) {
            self::push($r);
        } else {
            self::pull($r);
        }
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
        $sth->execute(['id' => $id]);

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a User entity
        $r = new Entities\Room();

        // Set the ID
        $r->setID($id);

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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(["property_id" => $property_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}