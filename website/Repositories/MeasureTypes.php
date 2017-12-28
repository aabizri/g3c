<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/27/17
 * Time: 6:06 PM
 */

namespace Repositories;

use Repositories\Exceptions\MultiSetFailedException;
use Repositories\Exceptions\RowNotFoundException;
use Repositories\Exceptions\SetFailedException;

class MeasureTypes extends Repository
{
    /**
     * @param \Entities\MeasureType $mt
     * @throws SetFailedException
     * @throws \Exception
     */
    public static function insert(\Entities\MeasureType $mt): void
    {
        // SQL
        $sql = "INSERT INTO measure_types (name,description,unit_name,unit_symbol,min,max)
          VALUES (:name,:description,:unit_name,:unit_symbol,min,max);";

        // Prepare statemnt
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare values
        $data = $mt->getMultiple([
            "type_id",
            "date_time",
            "value",
            "actuator_id",
            "sensor_id",
        ]);

        // Execute
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($mt->setID($id) === false) {
            throw new SetFailedException("MeasureType", "setID", $id);
        }
    }

    /**
     * @param \Entities\MeasureType $mt
     * @throws \Exception
     */
    public static function push(\Entities\MeasureType $mt): void
    {
        // SQL
        $sql = "UPDATE measure_types
        SET name = :name, description = :description, unit_name = :unit_name, unit_symbol = :unit_symbol, min = :min, max = :max
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $mt->getMultiple([
            "name",
            "description",
            "unit_name",
            "unit_symbol",
            "min",
            "max",
        ]);

        // Execute query
        $stmt->execute($data);
    }

    /**
     * @param \Entities\MeasureType $mt
     *
     * @throws MultiSetFailedException
     * @throws RowNotFoundException
     * @throws \Exception
     */
    public static function pull(\Entities\MeasureType $mt): void
    {
        // SQL
        $sql = "SELECT name,description,unit_name,unit_symbol,min,max
            FROM measure_types
            WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $stmt->execute(["id" => $mt->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new RowNotFoundException("MeasureType", "measure_types");
        }

        // Sanitise
        if ($data["min"] !== null) {
            $data["min"] = (double)$data["min"];
        }
        if ($data["max"] !== null) {
            $data["max"] = (double)$data["max"];
        }

        // Set
        $ok = $mt->setMultiple([
            "name" => (string)$data["name"],
            "description" => (string)$data["description"],
            "unit_name" => (string)$data["unit_name"],
            "unit_symbol" => (string)$data["unit_symbol"],
            "min" => $data["min"],
            "max" => $data["max"],
        ]);
        if (!$ok) {
            throw new MultiSetFailedException("MeasureType", $data);
        }
    }

    /**
     * Retrieves a MeasureType from the database
     *
     * @param int $id
     *
     * @return \Entities\MeasureType
     *
     * @throws MultiSetFailedException
     * @throws RowNotFoundException
     * @throws SetFailedException
     * @throws \Exception
     */
    public static function retrieve(int $id): \Entities\MeasureType
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM measure_types
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create entity
        $mt = new \Entities\MeasureType();

        // Set ID
        if ($mt->setID($id) === false) {
            throw new SetFailedException("MeasureType", "setID", $id);
        }

        // Pull
        self::pull($mt);
    }
}