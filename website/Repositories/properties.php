<?php


namespace Repositories;


use PDO;

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

    /**
     * Push an existing Property to the database
     *
     * @param \Entities\Property $p the Peripheral to push
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
        $data = [
            ':id' => $p->getID(),
            ':name' => $p->getName(),
            ':address' => $p->getAddress(),
        ];

        // We don't have the ID in the Push, as they are only updated by the attachToXXX methods

        // Execute query
        $sth->execute($data);

        self::pull($p);
    }

    /**
     * Pull an existing Entities\Property from the database
     *
     * @param \Entities\Property $p the peripheral to pull
     *
     * @return void
     *
     * @throws \Exception if there is no such Model\Peripheral
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
        $sth->execute(array(':id' => $p->getID()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\Property found");
        }

        // Store
        $arr = array(
            "setName" => $data["name"],
            "setAddress" => $data["address"],
            "setCreationDate" => $data["creation_date"],
            "setLastUpdated" => $data["last_updated"],
        );

        parent::executeSetterArray($p, $arr);
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
        $sth->execute(array(':id' => $id));

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

    /**
     * Find a Model\Property by name
     *
     * @param string $name the name with which to find the given Entity\PROPERTY_ID
     *
     * @return int the ID of the user in question, or null if none are found
     *
     * @throws \Exception if there is more than one user found with this name
     */
    public static function findByName(string $name): int
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM properties
            WHERE name = :name";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':name' => $name));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one row shares this name !");
        }

        // SQL for selecting
        $sql = "SELECT id
            FROM properties
            WHERE name = :name";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':name' => $name));

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }

    /**
     * Find a Model\Property by address
     *
     * @param string $address the email with which to find the given Entity\PROPERTY_ID
     *
     * @return int the ID of the user in question, or null if none are found
     *
     * @throws \Exception if there is more than one user found with this address
     */
    public static function findByAddress(string $address): int
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM properties
            WHERE address = :address";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':address' => $address));

        // Fetch
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