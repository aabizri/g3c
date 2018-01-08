<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 11/27/17
 * Time: 9:15 PM
 */

namespace Repositories;

use Entities;
use Exceptions\MultiSetFailedException;
use Exceptions\RowNotFoundException;
use Exceptions\SetFailedException;

class QuestionAnswer extends Repository {

    public static function insert(Entities\QuestionAnswer $r): void
    {
        $sql = "INSERT INTO faq (id, question, answer, creation_date)
        VALUES (:id, ;question, :answer, :creation_date)";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $r->getMultiple([
            "id",
            "question",
            "answer",
            "creation_date",
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // Pull
        self::pull($r);
    }

    public static function push(Entities\faq $r): void
    {
        // SQL
        $sql = "UPDATE faq
        SET id = :id, question = :question, answer = :answer, creation_date = :creation_date";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $r->getMultiple([
            "id",
            "question",
            "answer",
            "creation_date",
        ]);

        // Execute query
        $stmt->execute($data);

        // Pull
        self::pull($r);
    }

    public static function pull(Entities\Room $r): void
    {
        // SQL
        $sql = "SELECT question, answer, creation_date, UNIX_TIMESTAMP(last_updated) AS last_updated
        FROM faq
        WHERE id = :id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['id' => $r->getID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            new RowNotFoundException($r,"faq");
        }

        // Store
        $ok = $r->setMultiple([
            "id" => $data["id"],
            "question" => $data["question"],
            "answer" => $data["answer"],
            "creation_date" => $data["creation_date"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException($r, $data);
        }
    }

    public static function exists(int $id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM faq
            WHERE id = :id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['id' => $id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    public static function retrieve(int $id): ?Entities\QuestionAnswer
    {
        // If it doesn't exist, we return null
        if (!self::exists($id)) {
            return null;
        }

        // Create a User entity
        $r = new Entities\QuestionAnswer();

        // Set the ID
        $ok = $r->setID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setID",$id);
        }

        // Call Pull on it
        self::pull($r);

        // Return the user_id
        return $r;
    }


}