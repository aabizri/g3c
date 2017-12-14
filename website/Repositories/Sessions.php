<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 27/11/2017
 * Time: 11:56
 */

namespace Repositories;

use Entities;
use Exception;
use PDO;


class Sessions extends Repository
{
    /**
     * Inserts a Session to the database
     *
     * The Entities\Session doesn't have to have its ID set
     *
     * @param Entities\Session $s
     * @throws Exception
     */
    public static function insert(\Entities\Session $s): void
    {
        //On écrit une reqûete SQL
        $sql = "INSERT INTO sessions (id, user_id, started, expiry, canceled, value)
        VALUES (:id, :user_id, :started, :expiry, :canceled, :value);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être insérées
        $data = [
            'id' => $s->getID(),
            'user_id' => $s->getUserID(),
            'started' => $s->getStarted(),
            'expiry' => $s->getExpiry(),
            'canceled' => $s->getCanceled(),
            'value' => $s->getValue(),
        ];

        // Execute query
        $sth->execute($data);

        // Pull
        self::pull($s);
    }

    /**
     * Push an existing session to the database
     *
     * @param Entities\Session $s
     * @throws Exception
     */
    public static function push(Entities\Session $s): void
    {
        // SQL
        $sql = "UPDATE sessions
        SET user_id = :user_id, started = :started, expiry = :expiry, canceled = :canceled, value = :value
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = [
            "id" => $s->getID(),
            'user_id' => $s->getUserID(),
            'started' => $s->getStarted(),
            'expiry' => $s->getExpiry(),
            'canceled' => $s->getCanceled(),
            'value' => $s->getValue(),
        ];

        // Execute query
        $sth->execute($data);

        // Pull
        self::pull($s);
    }

    /**
     * Pull an existing session from the database
     *
     * @param Entities\Session $s
     * @throws Exception
     */
    public static function pull(Entities\Session $s): void
    {
        // SQL
        $sql = "SELECT user_id, value, started, expiry, canceled, last_updated
        FROM sessions
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $s->getID()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new Exception("Nous n'avons pas trouvé de session correspondante");
        }

        // Store
        $arr = array(
            "setUserID" => $data["user_id"],
            "setValue" => $data["value"],
            "setStarted" => $data["started"],
            "setExpiry" => $data["expiry"],
            "setCanceled" => $data["canceled"],
            "setLastUpdated" => $data["last_updated"],
        );
        parent::executeSetterArray($s, $arr);
    }

    /**
     * Syncs a session with the database, executing a Pull or a Push on a last_updated timestamp basis
     *
     * @param Entities\Session $s to be synced
     *
     * @return void
     *
     * @throws \Exception if not found
     */
    public static function sync(Entities\Session $s): void
    {
        // SQL to get last_updated on given peripheral
        $sql = "SELECT last_updated
          FROM sessions
          WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(array(':id' => $s->getID()));

        // Retrieve
        $db_last_updated = $sth->fetchColumn(0);

        // If nil, we throw an exception
        if ($db_last_updated == null) {
            throw new \Exception("No such session found");
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
     * Retrieve a session from the database given its id
     *
     * @param string $id of the session to retrieve
     * @return Entities\Session the it is found, null if not
     * @throws \Exception
     */
    public static function retrieve(string $id): ?Entities\Session
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM sessions
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

        // Create an entity
        $s = new Entities\Session;

        // Set the ID
        $s->setID($id);

        // Call Pull on it
        self::pull($s);

        // Return it
        return $s;
    }

    /**
     * Retrieves all IDs for session belonging to that user_id
     *
     * @param int $user_id
     * @return string[] array of session ids
     */
    public static function findAllByUserID(int $user_id): array
    {
        // SQL
        $sql = "SELECT id
            FROM sessions
            WHERE user_id = :user_id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":user_id" => $user_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}

