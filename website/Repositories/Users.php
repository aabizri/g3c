<?php

namespace Repositories;

use Entities;
use PDO;
use Repositories\Exceptions\MultiSetFailedException;
use Repositories\Exceptions\RowNotFoundException;
use Repositories\Exceptions\SetFailedException;

class Users extends Repository
{
    /**
     * Insert inserts a new user to the database
     *
     * @param Entities\User $u the user to be inserted
     *
     * @throws \Exception if the subsequent pull fails
     */
    public static function insert(Entities\User $u): void
    {
        // SQL
        $sql = "INSERT INTO users (display, nick, birth_date, email, password, phone)
        VALUES (:display, :nick, :birth_date, :email, :password_hashed, :phone)";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $u->getMultiple([
            'display',
            'nick',
            'birth_date',
            'email',
            'password_hashed',
            'phone',
        ]);

        // Execute creation query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $u->setID($id);
        if (!$ok) {
            throw new SetFailedException("User","setID",$id);
        }

        // We should now pull to populate ID & Times
        self::pull($u);
    }

    /**
     * Push an existing Model\User to the database
     *
     * @param Entities\User $u the user to push
     *
     * @throws \Exception if the subsequent pull fails
     */
    public static function push(Entities\User $u): void
    {
        // SQL
        $sql = "UPDATE users
        SET display = :display, nick = :nick, birth_date = :birth_date, email = :email, password = :password_hashed, phone = :phone";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be updated
        $data = $u->getMultiple([
            'display',
            'nick',
            'birth_date',
            'email',
            'password_hashed',
            'phone',
        ]);

        // Execute query
        $stmt->execute($data);

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
    public static function pull(Entities\User $u): void
    {
        // SQL
        $sql = "SELECT display, nick, birth_date, email, password, phone, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM users
        WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $u->getID()]);

        // Fetch
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if (empty($data)) {
            throw new RowNotFoundException("User","users");
        }

        // Store
        $ok = $u->setMultiple([
            "display" => $data["display"],
            "nick" => $data["nick"],
            "birth_date" => $data["birth_date"],
            "email" => $data["email"],
            "phone" => $data["phone"],
            "password_hashed" => $data["password"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException("User",$data);
        }
    }

    /**
     * Récupérer l'id d'un user
     * @param int $id
     * @return Entities\User ou null si rien n'est trouvé
     * @throws \Exception
     */
    public static function retrieve(int $id): Entities\User
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM users
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);

        // If count is zero, then we return null
        if ($count == 0) {
            return null;
        }

        // Create a User entity
        $u = new Entities\User();

        // Set the ID
        $ok = $u->setID($id);
        if (!$ok) {
            throw new SetFailedException("User","setID",$id);
        }

        // Call Pull on it
        self::pull($u);

        // Return the user_id
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
    public static function findByEmail(string $email): ?int
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM users
            WHERE email = :email";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Params
        $params = ['email' => $email];

        // Execute query
        $stmt->execute($params);

        // Fetch
        $count = $stmt->fetchColumn(0);

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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute($params);

        // Fetch
        $id = $stmt->fetchColumn(0);

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
    public static function findByNick(string $nick): ?int
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM users
            WHERE nick = :nick";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Parameters
        $params = ['nick' => $nick];

        // Execute query
        $stmt->execute($params);

        // Fetch
        $count = $stmt->fetchColumn(0);

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
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute($params);

        // Fetch
        $id = $stmt->fetchColumn(0);

        // Return this ID
        return $id;
    }
}