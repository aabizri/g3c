<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 06/01/2018
 * Time: 01:21
 */

namespace Repositories;


class Products extends Repository
{
    /**
     * Insert a new product to the database
     *
     * If it already exists, it fails.
     *
     * @param Entities\Product $p the Product to insert
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


}