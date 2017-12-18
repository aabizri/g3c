<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 27/11/2017
 * Time: 11:48
 */

namespace Repositories;


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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = [
            ":name" => $p->getName(),
            ":address" => $p->getAddress(),
        ];

        // Execute request
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($p->setId($id) == false) {
            throw new \Exception("error setting id");
        }

        // We should now pull to populate ID & Times
        self::pull($p);
    }

    public static function findbyAddress(string $address){

        // SQL
        $sql="SELECT count(*) FROM properties WHERE address=:address";

        //Prepare
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        $sth->execute(array(':address' => $address));

        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one row shares this address !");
        }

        // SQL for selecting
        $sql = "SELECT id
            FROM properties
            WHERE address = :address";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':address' => $address));

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }
}