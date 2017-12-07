<?php

namespace Repositories;

use Entities;
use Exception;
use PDO;

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
        $sql = "INSERT INTO peripherals (uuid, build_date, add_date, public_key, property_id, room_id)
        VALUES (:uuid, :build_date, :add_date, :public_key, :property_id, :room_id);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            ':uuid' => $p->getUUID(),
            ':build_date' => $p->getBuildDate(),
            ':add_date' => $p->getAddDate(),
            ':public_key' => $p->getPublicKey(),
            ':property_id' => $p->getPropertyId(),
            ':room_id' => $p->getRoomId(),
        ];

        // Execute query
        $sth->execute($data);
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = [
            ':uuid' => $p->getUUID(),
            ':display_name' => $p->getDisplayName(),
            ':build_date' => $p->getBuildDate(),
            ':add_date' => $p->getAddDate(),
            ':public_key' => $p->getPublicKey(),
        ]; // We don't have the ID in the Push, as they are only updated by the attachToXXX methods

        // Execute query
        $sth->execute($data);
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
        $sql = "SELECT display_name, build_date, add_date, public_key, property_id, room_id, last_updated
        FROM peripherals
        WHERE uuid = :uuid;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':uuid' => $p->getUUID()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            throw new Exception("No such Model\Peripheral found");
        }

        // Store
        $arr = array(
            "setDisplayName" => $data["display_name"],
            "setBuildDate" => $data["build_date"],
            "setAddDate" => $data["add_date"],
            "setPublicKey" => $data["public_key"],
            "setPropertyId" => $data["property_id"],
            "setRoomId" => $data["room_id"],
            "setLastUpdated" => $data["last_updated"],
        );
        parent::executeSetterArray($p, $arr);
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
        $sql = "SELECT last_updated
          FROM peripherals
          WHERE uuid = :uuid;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(array(':uuid' => $p->getUUID()));

        // Retrieve
        $db_last_updated = $sth->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated === null) {
            throw new Exception("No such Peripheral found");
        }

        // If empty, that's an Exception
        if ($db_last_updated === "") {
            throw new Exception("Empty last_updated");
        }

        // If the DB was updated BEFORE the last update to the peripheral, push
        if (strtotime($db_last_updated) < strtotime($p->getLastUpdated())) {
            self::push($p);
        } else {
            self::pull($p);
        }
    }

    /**
     * Retrieve a peripheral from the database given its id
     *
     * @param string $uuid UUID of the Peripheral to retrieve
     * @return Entities\Peripheral the peripheral if found, null if not
     * @throws Exception
     */
    public static function retrieve(string $uuid)
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM peripherals
            WHERE uuid = :uuid";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':uuid' => $uuid));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a Model\Peripheral
        $p = new Entities\Peripheral;

        // Set the UUID
        $ok = $p->setUUID($uuid);
        if ($ok == false) {
            throw new Exception("Error setting UUID in Peripheral");
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":property_id" => $property_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":room_id" => $room_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Attach the Peripheral to a Room
     *
     * It checks if the Room is linked to the same Property as the Peripheral, returns an Exception if it fails.
     *
     * @param Entities\Peripheral $p is the peripheral to link
     * @param int $roomID is the ID of the Room this Peripheral should be attached to
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array('room_id' => $roomID, ':uuid' => $p->getUUID()));

        // Check for sane row count of affected rows
        $rc = $sth->rowCount();
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
        $p->setRoomId($roomID);
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);
        $now = (new \Datetime)->format(\DateTime::ATOM);

        // Execute
        $sth->execute(array(':property_id' => $propertyID, ':add_date' => $now, ':uuid' => $p->getUUID()));

        // Set the ID and date
        $p->setPropertyId($propertyID);
        $p->setAddDate($now);
    }
}