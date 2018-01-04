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

class faq extends Repository {

    public static function insert(Entities\faq $r): void
    {
        $sql = "INSERT INTO faq (question_id, question, answer)
        VALUES (:question_id, ;question, :answer)";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Prepare data to be inserted
        $data = $r->getMultiple([
            "question_id",
            "question",
            "answer",
        ]);

        // Execute query
        $stmt->execute($data);

        // Get ID of the insert
        $id = parent::db()->lastInsertId();
        $ok = $r->setQuestionID($question_id);
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
        SET question_id = :question_id, question = :question, answer = :answer";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Data for the request
        $data = $r->getMultiple([
            "question_id",
            "question",
            "answer",
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
        WHERE question_id = :question_id;";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute statement
        $stmt->execute(['question_id' => $r->getQuestionID()]);

        // Retrieve
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If nil, we throw an error
        if ($data === false || $data === null) {
            new RowNotFoundException($r,"faq");
        }

        // Store
        $ok = $r->setMultiple([
            "question_id" => $data["question_id"],
            "question" => $data["question"],
            "answer" => $data["answer"],
            "creation_date" => $data["creation_date"],
            "last_updated" => (float)$data["last_updated"],
        ]);
        if ($ok === false) {
            throw new MultiSetFailedException($r, $data);
        }
    }

    public static function exists(int $question_id): bool
    {
        // SQL for counting
        $sql = "SELECT count(*)
            FROM faq
            WHERE question_id = :question_id";

        // Prepare statement
        $stmt = parent::db()->prepare($sql, parent::$pdo_params);

        // Execute query
        $stmt->execute(['question_id' => $question_id]);

        // Fetch
        $count = $stmt->fetchColumn(0);
        return $count != 0;
    }

    public static function retrieve(int $id): ?Entities\faq
    {
        // If it doesn't exist, we return null
        if (!self::exists($question_id)) {
            return null;
        }

        // Create a User entity
        $r = new Entities\faq();

        // Set the ID
        $ok = $r->setQuestionID($id);
        if (!$ok) {
            throw new SetFailedException($r,"setQuestionID",$question_id);
        }

        // Call Pull on it
        self::pull($r);

        // Return the user_id
        return $r;
    }


}