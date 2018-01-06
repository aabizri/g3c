<?php
/**
 * Created by PhpStorm.
 * User: arnoldrandy
 * Date: 27/11/2017
 * Time: 11:48
 */

namespace Repositories;


use Exceptions\MultiSetFailedException;
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
            throw new SetFailedException($p, "setID", $id);
        }

        // We should now pull to populate ID & Times
        self::pull($p);
    }

    /**
     * Push an existing Property to the database
     * @param \Entities\Property $p the property to push
     * @throws \Exception
     */
    public static function push(\Entities\Property $p)
    {
        // SQL
        $sql = "UPDATE properties
        SET name = :name, address = :address
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $p->getMultiple([
            'id',
            'name',
            'address',
        ]);

        // Execute query
        $sth->execute($data);

        self::pull($p);
    }

    /**
     * Pull an existing Entities\Property from the database
     *
     * @param \Entities\Property $p the property to pull
     *
     * @return void
     *
     * @throws \Exception if there is no such Model\Property
     */
    public static function pull(\Entities\Property $p)
    {
        // SQL
        $sql = "SELECT name, address, creation_date, last_updated
        FROM properties
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([':id' => $p->getID()]);

        // Retrieve
        $data = $sth->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Property found");
        }

        // Store
        $ok = $p->setMultiple([
            "setName" => $data["name"],
            "setAddress" => $data["address"],
            "setCreationDate" => (float)$data["creation_date"],
            "setLastUpdated" => (float)$data["last_updated"],
        ]);
        if (!$ok) {
            throw new MultiSetFailedException($p, $data);
        }
    }

    /**
     * Récupérer l'id d'une propriété
     * @param int $id
     * @return \Entities\Property ou null si rien n'est trouvé
     * @throws \Exception
     */
    public static function retrieve(int $id, $p): \Entities\Property
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM properties
            WHERE id = :id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute([':id' => $id]);

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a Property entity
        $u = new \Entities\Property();

        // Set the ID
        $u->setId($id);

        // Call Pull on it
        self::pull($p);

        // Return the user
        return $p;
    }
}