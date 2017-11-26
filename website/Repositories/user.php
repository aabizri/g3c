<?php

namespace Repositories;

use \Entities;

class User extends Repository {
    // Requêtes SQL
    private const CREATE_SQL= "INSERT INTO users (display, nick, birth_date, email, password, phone)
        VALUES (:display, :nick, :birth_date, :email, :password, :phone)";

    /**
     * Insert inserts a new user to the database
     *
     * @param Entities\User $u the user to be inserted
     */
    public static function insert(Entities\User $u)
    {
        // Prepare statement
        $sth = parent::db()->prepare(self::CREATE_SQL, parent::$pdo_params);

        // Execute creation query
        $sth->execute(array(':display' => $u->display, ':nick' => $u->nick, ':birth_date' => $u->birth_date, ':email' => $u->email, ':password' => $u->password_hashed, ':phone' => $u->phone));

        // We should now pull to populate ID & Times
        // TODO
    }

    /* TODO:
        - retrieve
        - pull
        - push
        - sync
    */
}