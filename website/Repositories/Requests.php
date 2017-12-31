<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 10:33 AM
 */

namespace Repositories;


use Repositories\Exceptions\MultiSetFailedException;
use Repositories\Exceptions\SetFailedException;

class Requests extends Repository
{
    /**
     * @param \Entities\Request $r
     * @throws \Exception
     */
    public static function insert(\Entities\Request $r): void
    {
        // SQL
        $sql = "INSERT INTO requests (ip, user_agent_txt, user_agent_hash, session_id, controller, method, action, in_debug, started_processing, duration, referer, request_uri, request_length, response_length)
        VALUES (:ip, :user_agent_txt, UNHEX(:user_agent_hash), :session_id, :controller, :method, :action, :in_debug, FROM_UNIXTIME(:started), :duration, :referer, :request_uri, :request_length, :response_length);";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être insérées
        $data = $r->getMultiple([
            'ip',
            'user_agent_txt',
            'user_agent_hash',
            'session_id',
            'controller',
            'method',
            'action',
            'in_debug',
            'started',
            'duration',
            'referer',
            'request_uri',
            'request_length',
            'response_length',
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException("Request","setID",$id);
        }
    }

    public static function push(\Entities\Request $r): void
    {

    }

    /**
     * @param \Entities\Request $r
     * @throws MultiSetFailedException
     * @throws \Exception
     */
    public static function pull(\Entities\Request $r): void
    {
        // SQL
        $sql = "SELECT ip, user_agent_txt, HEX(user_agent_hash) AS user_agent_hash, session_id, controller, method, action, in_debug, UNIX_TIMESTAMP(started_processing) AS started_processing, duration, referer, request_uri, request_length, response_length
            FROM requests
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $r->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new \Exception("Does not exist");
        }
        // Set data
        $ok = $r->setMultiple([
            'ip' => $data["ip"],
            'user_agent_txt' => $data["user_agent_txt"],
            'user_agent_hash' => $data["user_agent_hash"],
            'session_id' => $data["session_id"],
            'controller' => $data["controller"],
            'method' => $data["method"],
            'action' => $data["action"],
            'in_debug' => (bool)$data["in_debug"],
            'started' => (float)$data["started_processing"],
            'duration' => (int)$data["duration"],
            'referer' => $data["referer"],
            'request_uri' => $data["request_uri"],
            'request_length' => $data["request_length"],
            'response_length' => $data["response_length"],
        ]);
        if (!$ok) {
            throw new MultiSetFailedException("Request", $data);
        }
    }

    /**
     * Checks if the given request exists in the database
     *
     * @param int $id
     * @return bool
     */
    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM requests
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
     * Retrieve a room from the database given its id
     *
     * @param int $id of the room to retrieve
     * @return Entities\Request|null , null if it not found
     * @throws \Exception
     */
    public static function retrieve(int $id): \Entities\Request
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create a User entity
        $r = new \Entities\Request();

        // Set the ID
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException("Request", "setID", $id);
        }

        // Call Pull on it
        self::pull($r);

        // Return the user_id
        return $r;
    }

    public static function findAllBySessionID(string $session_id): array
    {

    }

    public static function findLastBySessionID(string $session_id): int
    {
        $sql = "SELECT id
            FROM requests
            WHERE session_id = :session_id
            ORDER BY started_processing DESC 
            LIMIT 1;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["session_id" => $session_id]);

        // Fetch all results
        $request_id = $stmt->fetchColumn(0);

        // Return the set
        return $request_id;
    }

    /**
     * Retrieves all IDs for session started by that IP
     *
     * @param string $ip
     * @return string[] array of session ids
     */
    public static function findAllByIP(string $ip): array
    {
        // SQL
        $sql = "SELECT id
            FROM requests
            WHERE ip = :ip;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(["ip" => $ip]);

        // Fetch all results
        $set = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}