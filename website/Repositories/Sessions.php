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
use Repositories\Exceptions\MultiSetFailedException;
use Repositories\Exceptions\RowNotFoundException;
use Repositories\Exceptions\SetFailedException;


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
        VALUES (:id, :user_id, FROM_UNIXTIME(:started), FROM_UNIXTIME(:expiry), :canceled, :value);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être insérées
        $data = $s->getMultiple([
            'id',
            'user_id',
            'started',
            'expiry',
            'canceled',
            'value',
        ]);

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
        SET user_id = :user_id, started = FROM_UNIXTIME(:started), expiry = FROM_UNIXTIME(:expiry), canceled = :canceled, value = :value
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être poussées
        $data = $s->getMultiple([
            'id',
            'user_id',
            'started',
            'expiry',
            'canceled',
            'value',
        ]);

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
        $sql = "SELECT user_id, value, UNIX_TIMESTAMP(started) AS started, UNIX_TIMESTAMP(expiry) AS expiry, canceled, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM sessions
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(['id' => $s->getID()]);

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new RowNotFoundException("Session","sessions");
        }

        // Store
        $ok = $s->setMultiple([
            "user_id" => $data["user_id"],
            "value" => $data["value"],
            "started" => (float)$data["started"],
            "expiry" => (float)$data["expiry"],
            "canceled" => $data["canceled"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException("Session",$data);
        }
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
        $sql = "SELECT UNIX_TIMESTAMP(last_updated) AS last_updated
          FROM sessions
          WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute
        $sth->execute(['id' => $s->getID()]);

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

        // Cast it to float
        $db_last_updated = (float)$db_last_updated;

        // If the DB was updated BEFORE the last update to the peripheral, push
        if ($db_last_updated < $s->getLastUpdated()) {
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
        $sth->execute(['id' => $id]);

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create an entity
        $s = new Entities\Session;

        // Set the ID
        $ok = $s->setID($id);
        if (!$ok) {
            throw new SetFailedException("Session","setID",$id);
        }

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
        $sth->execute(["user_id" => $user_id]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}

