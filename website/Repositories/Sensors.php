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

        // We don't have the ID in the Push, as they are only updated by the attachToXXX methods

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

}