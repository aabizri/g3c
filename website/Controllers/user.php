<?php
namespace Controllers;

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

        // Check if an entity with the same e-mail and/or nickname exists
        if (\Repositories\Users::findByNick($nick) != null) {
            echo "A user with this nick already exists";
        } else if (\Repositories\Users::findByEmail($email) != null) {
            echo "A user with this email already exists";
        }

        // Create the entity
        $u = new \Entities\User($nick,$email,$password_clear);

        // Insert it
        try {
            \Repositories\Users::insert($u);
        } catch (\Exception $e) {
            echo "Error inserting user".$e;
        }
    }
}