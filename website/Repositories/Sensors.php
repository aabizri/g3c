<?php
/**
 * Created by PhpStorm.
 * User: Dinesh
 * Date: 13/12/2017
 * Time: 16:52
 */

namespace Repositories;


use PDO;

class Sensors extends Repository
{
    /**
     * insert adds an entity to the database
     * @param \Entities\Sensor $s
     * @throws \Exception
     */
    public static function insert(\Entities\Sensor $s): void
    {
        // SQL
        $sql = "INSERT INTO sensors (sense_type)
          VALUES (:sense_type);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            ":sense_type" => $s->getSenseType(),
        ];

        // Execute request
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($s->setId($id) == false) {
            throw new \Exception("error setting id");
        }

        // We should now pull to populate ID & Times
        self::pull($s);
    }

    /**
     * Push an existing Sensor to the database
     * @param \Entities\Sensor $s the Peripheral to push
     * @throws \Exception
     */

    public static function push(\Entities\Sensor $s)
    {
        // SQL
        $sql = "UPDATE sensors
        SET sense_type = :sense_type
        WHERE id = :id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = [
            ':id' => $s->getID(),
            ':sense_type' => $s->getSenseType(),
        ];


        // Execute query
        $sth->execute($data);

        self::pull($s);
    }

    /**
     * Pull an existing Entities\Sensor from the database
     * @param \Entities\Sensor $s the property to pull
     * @return void
     * @throws \Exception if there is no such Model\Sensor
     */

    public static function pull(\Entities\Sensor $s)
    {
        // SQL
        $sql = "SELECT sense_type, last_measure, last_updated
        FROM sensors
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $s->getID()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\ Sensor found");
        }

        // Store
        $arr = array(
            "setSenseType" => $data["sense_type"],
            "setLastMeasure" => $data["last_measure"],
            "setLastUpdated" => $data["last_updated"],
        );

        parent::executeSetterArray($s, $arr);
    }

    /**
     * Syncs a sensor with the database, executing a Pull or a Push on a last_updated timestamp basis
     * @param \Entities\Sensor $s to be synced
     * @return void
     * @throws \Exception if not found
     */

    public static function sync(\Entities\Sensor $s): void
    {
        // SQL to get last_updated on given peripheral
        $sql = "SELECT last_updated
          FROM sensors
          WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(array(':id' => $s->getId()));

        // Retrieve
        $db_last_updated = $sth->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated == null) {
            throw new \Exception("No such Sensor found");
        }

        // If empty, that's an Exception
        if ($db_last_updated == "") {
            throw new \Exception("Empty last_updated");
        }

        // If the DB was updated BEFORE the last update to the peripheral, push
        if (strtotime($db_last_updated) < strtotime($s->getLastUpdated())) {
            self::push($s);
        } else {
            self::pull($s);
        }
    }

    /**
     * Retrieve a sensor from the database given its id
     * @param int $id of the sensor to retrieve
     * @return \Entities\Sensor the sensor if found, null if not
     * @throws \Exception
     */
    public static function retrieve(int $id): \Entities\Room
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM sensors
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

        // Create a Sensor entity
        $s = new \Entities\Sensor();

        // Set the ID
        $s->setId($id);

        // Call Pull on it
        self::pull($s);

        // Return the user
        return $s;
    }


    /*  Return all the sensor_id associated to a room
        @param int $room_id
        @return
    */

    public static function findAllByRoomID(int $room_id): array
    {
        // SQL
        $sql = "SELECT id
            FROM sensors WHERE peripheral_uuid
            IN (SELECT peripheral_uuid FROM peripherals WHERE room_id=:room_id);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":room_id" => $room_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;

    }

}