<?php

namespace Repositories;

use Entities;
use Exception;
use PDO;
use Repositories\Exceptions\MultiSetFailedException;
use Repositories\Exceptions\RowNotFoundException;
use Repositories\Exceptions\SetFailedException;

class Peripherals extends Repository
{
    /**
     * Insert a new Peripheral to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Peripheral $p the Peripheral to insert
     * @throws Exception
     */
    public static function insert(Entities\Peripheral $p)
    {
        // SQL
        $sql = "INSERT INTO peripherals (uuid, display_name, build_date, add_date, public_key, property_id, room_id)
        VALUES (:uuid, :display_name, :build_date, :add_date, :public_key, :property_id, :room_id);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $p->getMultiple([
            'uuid',
            'display_name',
            'build_date',
            'add_date',
            'public_key',
            'property_id',
            'room_id',
        ]);

        // Execute query
        $stmt->execute($data);
    }

    /**
     * Push an existing Model\Peripheral to the database
     *
     * @param Entities\Peripheral $p the Peripheral to push
     * @throws Exception
     */
    public static function push(Entities\Peripheral $p)
    {
        // SQL
        $sql = "UPDATE peripherals
        SET display_name = :display_name, build_date = :build_date, add_date = :add_date, public_key = :public_key
        WHERE uuid = :uuid;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $p->getMultiple([
            'uuid',
            'display_name',
            'build_date',
            'add_date',
            'public_key',
        ]); // We don't have the ID in the Push, as they are only updated by the attachToXXX methods

        // Execute query
        $stmt->execute($data);
    }

    /**
     * Pull an existing Entities\Peripheral from the database
     *
     * @param Entities\Peripheral $p the peripheral to pull
     *
     * @return void
     *
     * @throws Exception if there is no such Model\Peripheral
     */
    public static function pull(Entities\Peripheral $p)
    {
        // SQL
        $sql = "SELECT display_name, build_date, add_date, public_key, property_id, room_id, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM peripherals
        WHERE uuid = :uuid;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['uuid' => $p->getUUID()]);

        // Retrieve
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            throw new RowNotFoundException("Peripheral", "peripherals");
        }

        // Store
        $ok = $p->setMultiple([
            "display_name" => $data["display_name"],
            "build_date" => $data["build_date"],
            "add_date" => $data["add_date"],
            "public_key" => $data["public_key"],
            "property_id" => $data["property_id"],
            "room_id" => $data["room_id"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if (!$ok) {
            throw new MultiSetFailedException("Peripherals", $data);
        }
    }

    /**
     * Syncs a Model\Peripheral with the database, executing a Pull or a Push on a last_updated timestamp basis
     *
     * @param Entities\Peripheral $p to be synced
     *
     * @return void
     *
     * @throws \Exception if not found
     */
    public static function sync(Entities\Peripheral $p)
    {
        // SQL to get last_updated on given peripheral
        $sql = "SELECT UNIX_TIMESTAMP(last_updated) AS last_updated
          FROM peripherals
          WHERE uuid = :uuid;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $stmt->execute(['uuid' => $p->getUUID()]);

        // Retrieve
        $db_last_updated = $stmt->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated === null) {
            throw new RowNotFoundException("Peripheral", "peripherals");
        }

        // If empty, that's an Exception
        if ($db_last_updated === "") {
            throw new Exception("Empty last_updated");
        }

        // Cast it
        $db_last_updated = (float)$db_last_updated;

        // If the DB was updated BEFORE the last update to the peripheral, push
        if ($db_last_updated < $p->getLastUpdated()) {
            self::push($p);
        } else {
            self::pull($p);
        }
    }

    /**
     * Checks if the given peripheral exists in the database
     *
     * @param string $uuid
     * @return bool
     */
    public static function exists(string $uuid): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM peripherals
            WHERE uuid = :uuid";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['uuid' => $uuid]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    /**
     * Retrieve a peripheral from the database given its id
     *
     * @param string $uuid UUID of the Peripheral to retrieve
     * @return Entities\Peripheral|null the peripheral if found, null if not
     * @throws Exception
     */
    public static function retrieve(string $uuid): ?\Entities\Peripheral
    {
        // If it doesn't exist, we return null
        if (!self::exists($uuid)) {
            return null;
        }

        // Create a Model\Peripheral
        $p = new Entities\Peripheral;

        // Set the UUID
        $ok = $p->setUUID($uuid);
        if (!$ok) {
            throw new SetFailedException("Peripheral", "setUUID", $uuid);
        }

        // Call Pull on it
        self::pull($p);

        // Return the peripheral
        return $p;
    }

    /**
     * Retrieves all IDs for peripherals belonging to that property
     *
     * @param int $property_id
     * @return string[] array of uuids
     */
    public static function findAllByPropertyID(int $property_id): array
    {
        // SQL
        $sql = "SELECT uuid
            FROM peripherals
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

    /**
     * Retrieves all IDs for peripherals belonging to that room
     *
     * @param int $room_id
     * @return string[] array of uuids
     */
    public static function findAllByRoomID(int $room_id): array
    {
        // SQL
        $sql = "SELECT uuid
            FROM peripherals
            WHERE room_id = :room_id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["room_id" => $room_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Attach the Peripheral to a Room.php
     *
     * It checks if the Room.php is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param Entities\Peripheral $p is the peripheral to link
     * @param int $roomID is the ID of the Room.php this Peripheral should be attached to
     *
     * @return void
     *
     * @throws Exception if the conditions aren't set
     */
    public static function attachToRoom(Entities\Peripheral $p, int $roomID)
    {
        // SQL
        $sql = "UPDATE peripherals
        SET room_id = :room_id
        WHERE uuid = :uuid AND property_id =
            (SELECT property_id
                FROM rooms
                WHERE id = :room_id);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // SEt parameters
        $params = $p->getMultiple([
            'room_id',
            'uuid',
        ]);

        // Execute query
        $stmt->execute($params);

        // Check for sane row count of affected rows
        $rc = $stmt->rowCount();
        switch ($rc) {
            case 0:
                throw new Exception("Conditions not set, are the peripheral & room attached to the right property ?");
                break;
            case 1: // Perfect, continue
                break;
            default:
                throw new Exception("More than 1 affected record, this is not normal, aborting !");
                break;
        }

        // Set the ID and date
        $ok = $p->setRoomID($roomID);
        if (!$ok) {
            throw new SetFailedException("Peripheral", "setRoomID", $roomID);
        }
    }

    /**
     * Attach the Peripheral to a Property
     *
     * @param Entities\Peripheral $p is the Peripheral to be attached to a Property
     * @param int $propertyID is the ID of the Property this Peripheral should be attached to
     *
     * @return void
     *
     * @throws Exception
     */
    public static function attachToProperty(Entities\Peripheral $p, int $propertyID)
    {
        // SQL
        $sql = "UPDATE peripherals
        SET property_id = :property_id, add_date = :add_date
        WHERE uuid = :uuid;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);
        $now = (new \Datetime)->format(\DateTime::ATOM);

        // Execute
        $stmt->execute([
            ':property_id' => $propertyID,
            ':add_date' => $now,
            ':uuid' => $p->getUUID()
        ]);

        // Set the ID and date
        $data = [
            "property_id" => $propertyID,
            "add_date" => $now,
        ];
        $ok = $p->setMultiple($data);
        if (!$ok) {
            throw new MultiSetFailedException("Peripherals", $data);
        }
    }
}

Peripherals::count("017de9be-3fe8-4613-98b1-d0eeefbe4887");