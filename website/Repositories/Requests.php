<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/7/17
 * Time: 10:33 AM
 */

namespace Repositories;


class Requests extends Repository
{
    public static function insert(\Entities\Request $r): void
    {
        // SQL
        $sql = "INSERT INTO requests (ip, user_agent_txt, user_agent_hash, session_id, controller, action, started_processing, finished_processing)
        VALUES (:ip, :user_agent_txt, UNHEX(:user_agent_hash), :session_id, :controller, :action, FROM_UNIXTIME(:started), FROM_UNIXTIME(:finished));";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // On prépare les données qui vont être insérées
        $data = [
            'ip' => $r->getIp(),
            'user_agent_txt' => $r->getUserAgentText(),
            'user_agent_hash' => $r->getUserAgentHash(),
            'session_id' => $r->getSessionID(),
            'controller' => $r->getController(),
            'action' => $r->getAction(),
            'started' => $r->getStarted(),
            'finished' => $r->getFinished(),
        ];

        // Execute query
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($r->setId($id) == false) {
            throw new \Exception("error setting id");
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
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute([":ip" => $ip]);

        // Fetch all results
        $set = $sth->fetchAll(\PDO::FETCH_COLUMN, 0);

        // Return the set
        return $set;
    }
}