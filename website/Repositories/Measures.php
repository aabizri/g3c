<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/17/17
 * Time: 1:55 PM
 */

namespace Repositories;


class Measures extends Repository
{
    public static function insert(\Entities\Measure $m): void
    {
        // SQL
        $sql = "INSERT INTO measures (type, date_time, value, peripheral_uuid)
          VALUES (:type,:date_time,:value,:peripheral_uuid);";

        // Prepare statemnt
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare values
        $data = [
            "type" => $m->getType(),
            "date_time" => $m->getDateTime(),
            "value" => $m->getValue(),
            "peripheral_uuid" => $m->getPeripheralUUID(),
        ];

        // Execute
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($m->setID($id) === false) {
            throw new \Exception("error setting id");
        }

        // Pull
        self::pull($m);
    }

    public static function pull(\Entities\Measure $m): void
    {
        // SQL
        $sql = "SELECT type,date_time,value,peripheral_uuid, UNIX_TIMESTAMP(last_updated) AS last_updated
            FROM measures
            WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $stmt->execute([":id" => $m->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Peripheral found");
        }

        // Store
        $set_order = [
            "setID" => $data["id"],
            "setType" => $data["type"],
            "setDateTime" => $data["date_time"],
            "setValue" => $data["value"],
            "setPeripheralUUID" => $data["peripheral_uuid"],
            "setLastUpdated" => $data["last_updated"]

        ];
        parent::executeSetterArray($m, $set_order);
    }

    /**
     * Checks if the given measure exists in the database
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM measures
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    public static function retrieve(int $id): \Entities\Measure
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create entity
        $m = new \Entities\Measure();

        // Set ID
        if ($m->setID($id) === false) {
            throw new \Exception("couldn't set ID");
        }

        // Pull
        self::pull($m);
    }

    /**
     * findAllByPeripheralUUID retrieves all IDs for measures generated by that peripheral
     * It is sorted by Descending Time
     *
     * @param string $peripheral_uuid
     * @return int[] array of measure ids
     */
    public static function findAllByPeripheralUUID(string $peripheral_uuid): array
    {
        // SQL
        $sql = "SELECT id
            FROM measures
            WHERE peripheral_uuid = :peripheral_uuid
            ORDER BY date_time DESC;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute();

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}