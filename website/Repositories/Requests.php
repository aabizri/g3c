<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 10:33 AM
 */

namespace Repositories;


use Exceptions\SetFailedException;

class Requests extends Repository
{
    /**
     * @param \Entities\Request $r
     * @throws \Exception
     */
    public static function insert(\Entities\Request $r): void
    {
        // SQL
        $sql = "INSERT INTO requests (ip, user_agent_txt, user_agent_hash, session_id, controller, action, started_processing, finished_processing)
        VALUES (:ip, :user_agent_txt, UNHEX(:user_agent_hash), :session_id, :controller, :action, FROM_UNIXTIME(:started), FROM_UNIXTIME(:finished));";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être insérées
        $data = $r->getMultiple([
            'ip',
            'user_agent_txt',
            'user_agent_hash',
            'session_id',
            'controller',
            'action',
            'started',
            'finished',
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }
    }

    public static function push(\Entities\Request $r): void
    {

    }

    public static function pull(\Entities\Request $r): void
    {

    }

    public static function retrieve(int $id): \Entities\Request
    {

    }

    public static function findAllBySessionID(string $session_id): array
    {

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