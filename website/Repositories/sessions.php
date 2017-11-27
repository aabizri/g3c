<?php
/**
 * Created by PhpStorm.
 * User: Eytan
 * Date: 27/11/2017
 * Time: 11:56
 */

namespace Repositories;

    use \Entities;
    use \PDO;
    use \Exception;


class Sessions extends Repository
{
    public static function insert(\Entities\Session $s)
    {   // On prépare les données qui vont être insérées
        $data = [
            'user' => $s->getUser(),
            'started' => $s->getStarted(),
            'expiry' => $s->getExpiry(),
            'canceled' => $s->getCancelled(),
            'ip' => $s->getIp(),
            'user_agent_txt' => $s->getUserAgentTxt(),
            'user_agent_hash' => $s->getUserAgentHash(),
            'cookie' => $s->getCookie(),
        ];

        //On exécute une reqûete SQL
        $sql = "INSERT INTO sessions (user, started, expiry, canceled, ip, user_agent_txt, user_agent_hash, cookie)
        VALUES (:user, :started, :expiry, :canceled, :ip, :user_agent_txt, :user_agent_hash, :cookie);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($s->setId($id) == false) {
            throw new \Exception("error setting id");
        }

        // Pull
        self::pull($s);
    }

    public static function pull(Entities\Session $s)
    {
        // SQL
        $sql =  "SELECT id, user, started, expiry, canceled, ip, user_agent_txt, user_agent_hash, cookie, last_updated
        FROM sessions
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $s->getId()));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new Exception("Nous n'avons pas trouvé de session correspondante");
        }

        // Store
        $arr = array(
            "setId" => $data["id"],
            "setUser" => $data["user"],
            "setStarted" => $data["started"],
            "setExpiry" => $data["expiry"],
            "setCancelled" => $data["canceled"],
            "setIp" => $data["ip"],
            "setUserAgentTxt" => $data["user_agent_txt"],
            "setUserAgentHash" => $data["user_agent_hash"],
            "setCookie" => $data["cookie"],
            "setLastUpdated" => $data["last_updated"],
        );
        parent::executeSetterArray($s,$arr);
    }

    }

