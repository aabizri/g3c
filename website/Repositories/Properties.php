<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 27/11/2017
 * Time: 11:48
 */

namespace Repositories;


use Exceptions\SetFailedException;

class Properties extends Repository
{
    /**
     * insert adds an entity to the database
     *
     * @param \Entities\Property $p
     * @throws \Exception
     */
    public static function insert(\Entities\Property $p): void
    {
        // SQL
        $sql = "INSERT INTO properties (name, address)
          VALUES (:name, :address);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $p->getMultiple([
            "name",
            "address",
        ]);

        // Execute request
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $p->setID($id);
        if (!$ok) {
            throw new SetFailedException($p,"setID",$id);
        }

        // We should now pull to populate ID & Times
        self::pull($p);
    }
}