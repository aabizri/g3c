<?php
namespace Controllers;

use \Repositories;

/**
 * Class User
 * @package Controllers
 */
class User
{
    /**
     * join subscribes a user
     * @throws \Exception
     */
    public function join() {
        // Get the data
        $nick = $_POST["nick"];
        $email = $_POST["email"];
        $password_clear = $_POST["password"];

        // Check if an entity with the same nick exists
        $nickDuplicate = Repositories\Users::findByNick($nick) != null;
        if ($nickDuplicate) {
            echo "A user with this nick already exists";
            return;
        }

        // Check if an entity with the same email exists
        $emailDuplicate = Repositories\Users::findByEmail($email) != null;
        if ($emailDuplicate) {
            echo "A user with this email already exists";
            return;
        }

        // Create the entity
        $u = new \Entities\User($nick,$email,$password_clear);

        // Insert it
        try {
            Repositories\Users::insert($u);
        } catch (\Exception $e) {
            echo "Error inserting user".$e;
        }
    }
}