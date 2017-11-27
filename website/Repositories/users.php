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
        // SQL
        $sql = "INSERT INTO users (display, nick, birth_date, email, password, phone)
        VALUES (:display, :nick, :birth_date, :email, :password, :phone)";

        // Prepare statement
        $sth = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = [
            ':display' => $u->getDisplay(),
            ':nick' => $u->getNick(),
            ':birth_date' => $u->getBirthDate(),
            ':email' => $u->getEmail(),
            ':password' => $u->getPasswordHashed(),
            ':phone' => $u->getPhone(),
        ];

        // Execute creation query
        $sth->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        if ($u->setId($id) == false) {
            throw new \Exception("error setting id");
        }

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
        // SQL
        $sql = "UPDATE users
        SET display = :display, nick = :nick, birth_date = :birth_date, email = :email, password = :password, phone = :phone";

        // Prepare statement
        $sth = parent::db()->prepare($sql,parent::$pdo_params);

        // Prepare data to be updated
        $data = array(
            ':id' => $u->getId(),
            ':display' => $u->getDisplay(),
            ':nick' => $u->getNick(),
            ':birth_date' => $u->getBirthDate(),
            ':email' => $u->getEmail(),
            ':password' => $u->getPasswordHashed(),
            ':phone' => $u->getPhone(),
        );

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
        $sth->execute(array(':id' => $u->getId()));

        // Fetch
        $data = $sth->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data == null) {
            throw new \Exception("No such Model\User found");
        }

        // Store
        $arr = array(
            "setDisplay" => $data["display"],
            "setNick" => $data["nick"],
            "setBirthDate" => $data["birth_date"],
            "setEmail" => $data["email"],
            "setPhone" => $data["phone"],
            "setPasswordHashed" => $data["password"],
            "setLastUpdated" => $data["last_updated"],
        );
        foreach ($arr as $setter => $datum) {
            $success = $u->$setter($datum);
            if ($success == false) {
                throw new \Exception("Error with setter ".$setter." with value : ".$datum." (".gettype($datum).")");
            }
        }
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
        $sth->execute(array(':email' => $nick));

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