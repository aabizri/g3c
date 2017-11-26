<?php

namespace Repositories;

use \Entities;
use \PDO;

class Users extends Repository {
    /**
     * Insert inserts a new user to the database
     *
     * @param Entities\User $u the user to be inserted
     */
    public static function insert(Entities\User $u)
    {
        // Prepare data to be updated
        $data = array(
            ':display' => $u->display,
            ':nick' => $u->nick,
            ':birth_date' => $u->birth_date,
            ':email' => $u->email,
            ':password' => $u->password_hashed,
            ':phone' => $u->phone,
        );

        // SQL
        $sql = "INSERT INTO users (display, nick, birth_date, email, password, phone)
        VALUES (:display, :nick, :birth_date, :email, :password, :phone)";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute creation query
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $u->id = $id;

        // We should now pull to populate ID & Times
        self::pull($u);
    }

    /* TODO:
        - retrieve
        - sync
    */

    /**
     * Push an existing Model\User to the database
     *
     * @param Entities\User $u the user to push
     */
    public static function push(Entities\User $u)
    {
        // Prepare data to be updated
        $data = array(
            ':id' => $u->id,
            ':display' => $u->display,
            ':nick' => $u->nick,
            ':birth_date' => $u->birth_date,
            ':email' => $u->email,
            ':password' => $u->password_hashed,
            ':phone' => $u->phone,
            );

        // SQL
        $sql = "UPDATE users
        SET display = :display, nick = :nick, birth_date = :birth_date, email = :email, password = :password, phone = :phone";

        // Prepare statement
        $sth = parent::db()->prepare($sql,parent::$pdo_params);

        // Execute query
        $sth->execute($data);

        // Now pull
        self::pull($u);
    }

    /**
     * Pull an existing Model\User from the database
     *
     * @param Entities\User $u the user to pull
     */
    public static function pull(Entities\User $u) {
        // SQL
        $sql = "SELECT display, nick, birth_date, email, password, phone, last_updated
        FROM users
        WHERE id = :id";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':id' => $u->id));

        // Fetch
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new Exception("No such Model\User found");
        }

        // Store
        $u->display = $data["display"];
        $u->nick = $data["nick"];
        $u->birth_date = $data["birth_date"];
        $u->email = $data["email"];
        $u->phone = $data["phone"];
        $u->password_hashed = $data["password"];
        $u->last_updated = $data["last_updated"];
    }
}