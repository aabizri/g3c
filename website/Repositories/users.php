<?php

namespace Repositories;

use \Entities;
use \PDO;

class Users extends Repository {
    /**
     * Insert inserts a new user to the database
     *
     * @param Entities\User $u the user to be inserted
     *
     * @throws \Exception if the subsequent pull fails
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
     *
     * @throws \Exception if the subsequent pull fails
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
     *
     * @throws \Exception if no such Model\User is found
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
            throw new \Exception("No such Model\User found");
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

    /**
     * Récupérer l'id d'un nick
     * @param int $id
     * @return Entities\User
     * @throws \Exception
     */
    public static function retrieve(int $id): Entities\User{
        // Create a User entity
        $u = new Entities\User();

        // Set the ID
        $u->id=$id;

        // Call Pull on it
        try {
            self::pull($u);
        } catch (\Exception $e) {
            return null;
        }

        // Return the user
        return $u;
    }

    /**
     * Find a Model\User by email
     *
     * @param string $email the email with which to find the given Entity\User ID
     *
     * @return int the ID of the user in question, or null if none are found
     *
     * @throws \Exception if there is more than one user found with this email
    */
    public static function findByEmail(string $email): int {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM users
            WHERE email = :email";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':email' => $email));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one row shares this e-mail !");
        }

        // SQL for selecting
        $sql = "SELECT id
            FROM users
            WHERE email = :email";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':email' => $email));

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }

    /**
     * Find a Model\User by its nickname
     *
     * @param string $nick the nick with which to find the given Entity\User ID
     *
     * @return int the ID of the user in question, or null if none are found
     *
     * @throws \Exception if there is more than one user found with this nickname
     */
    public static function findByNick(string $nick): int {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM users
            WHERE nick = :nick";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':nick' => $nick));

        // Fetch
        $count = $sth->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        } else if ($count > 1) {
            throw new \Exception("More than one row shares this nickname !");
        }

        // SQL for selecting
        $sql = "SELECT id
            FROM users
            WHERE nick = :nick";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $sth->execute(array(':nick' => $nick));

        // Fetch
        $id = $sth->fetchColumn(0);

        // Return this ID
        return $id;
    }
}