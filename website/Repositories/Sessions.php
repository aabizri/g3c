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
use Exceptions\MultiSetFailedException;
use Exceptions\RowNotFoundException;
use Exceptions\SetFailedException;


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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

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
        $stmt->execute($data);

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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

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
        $stmt->execute($data);

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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $s->getID()]);

        // Retrieve
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new RowNotFoundException($s,"sessions");
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
            throw new MultiSetFailedException($s,$data);
        }
    }

    /**
     * Checks if the given session exists in the database
     *
     * @param string $id
     * @return bool
     */
    public static function exists(string $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM sessions
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    /**
     * Retrieve a session from the database given its id
     *
     * @param string $id of the session to retrieve
     * @return Entities\Session|null , null if it is not found
     * @throws \Exception
     */
    public static function retrieve(string $id): ?Entities\Session
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create an entity
        $s = new Entities\Session;

        // Set the ID
        $ok = $s->setID($id);
        if (!$ok) {
            throw new SetFailedException($s,"setID",$id);
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
            WHERE user_id = :user_id
            ORDER BY started DESC;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["user_id" => $user_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }

    /**
     * Retrieves all IDs for session belonging to that user_id and that are valid
     *
     * @param int $user_id
     * @return string[] array of session ids
     */
    public static function findAllValidByUserID(int $user_id): array
    {
        // SQL
        $sql = "SELECT id
            FROM sessions
            WHERE user_id = :user_id AND canceled = FALSE AND expiry > now()
            ORDER BY started DESC;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["user_id" => $user_id]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}

