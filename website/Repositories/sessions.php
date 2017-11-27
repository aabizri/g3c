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
    public static function insert(\Entities\Sessions $s)
    {   // On prépare les données qui vont être insérées
        $data = [
            'id' => $s->id,
            'user' => $s->user,
            'started' => $s->started,
            'expiry' => $s->expiry,
            'canceled' => $s->canceled,
            'ip' => $s->ip,
            'user_agent_txt' => $s->user_agent_txt,
            'user_agent_hash' => $s->user_agent_hash,
            'cookie' => $s->cookie,
            'last_updated' => $s->last_updated,

        ];

        //On exécute une reqûete SQL
        $sql = "INSERT INTO sessions (id, user, ip, cookie)
        VALUES (:id, :user, :ip, :cookie);";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute($data);

        // Pull
        self::pull($s);
    }

    public static function pull(Entities\Sessions $s)
    {
        // SQL
        $sql =  "SELECT id, user, started, expiry, canceled, ip, user_agent_txt, user_agent_hash, cookie, last_updated
        FROM sessions
        WHERE id = :id;";

        // Prepare statement
        $sth = parent::db()->prepare(self::PULL_SQL, parent::$pdo_params);

        // Execute statement
        $sth->execute(array(':id' => $s->id));

        // Retrieve
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new Exception("Nous n'avons pas trouvé de session correspondante");
        }

        // Store
        $s->id = $data["id"];
        $s->user = $data["user"];
        $s->started = $data["started"];
        $s->expiry = $data["expiry"];
        $s->cancelled = $data["cancelled"];
        $s->ip = $data["ip"];
        $s->user_agent_txt = $data["user_agent_txt"];
        $s->user_agent_hash = $data["user_agent_hash"]
        $s->cookie = $data["cookie"]
        $s->last_updated = $data["last_updated"]

    }

    }

