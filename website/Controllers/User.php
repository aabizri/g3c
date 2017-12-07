<?php

namespace Controllers;

use Repositories;
use Entities;

/**
 * Class User
 * @package Controllers
 */
class User
{
    /**
     * join subscribes a user_id
     * @throws \Exception
     */
    public function join(array $get, array $post): void
    {
        // Check if the data exists
        $required = ["nick", "email", "password", "name", "surname", "phone"];
        foreach ($required as $key) {
            if (empty($post[$key])){
                echo "Missing key: ".$key;
                return;
            }
        }

        // Assign values
        $nick = $post["nick"];
        $email = $post["email"];
        $password_clear = $post["password"];
        $display = $post["name"] . " " . $_POST["surname"];
        $phone = $post["phone"];

        /**
         * Check if an entity with the same nick exists
         * @var int $nickDuplicate
         */
        $nickDuplicate = Repositories\Users::findByNick($nick) != null;
        if ($nickDuplicate) {
            echo "A user_id with this nick already exists";
            return;
        }

        /**
         * Check if an entity with the same email exists
         * @var int $emailDuplicate
         */
        $emailDuplicate = Repositories\Users::findByEmail($email) != null;
        if ($emailDuplicate) {
            echo "A user_id with this email already exists";
            return;
        }

        // Create the entity
        $u = new Entities\User();
        $u->setNick($nick);
        $u->setEmail($email);
        $u->setPassword($password_clear);
        $u->setDisplay($display);
        $u->setPhone($phone);

        // Insert it
        try {
            Repositories\Users::insert($u);
        } catch (\Exception $e) {
            echo "Error inserting user_id" . $e;
        }
    }

    /**
     * Connexion
     */
    public function connexion(array $get, array $post): void
    {
        // Check if the data exists
        $required = ["nick", "password"];
        foreach ($required as $key) {
            if (empty($post[$key])){
                echo "Missing key: ".$key;
                return;
            }
        }

        // Récupérer les données
        $nick = $_POST['nick'];
        $password_clear = $_POST['password'];

        /**
         * Vérifier que le nick et/ou e-mail existe
         * @var int $id
         */
        $id = Repositories\Users::findByNick($nick); //trouve l'id lié au nickname
        if ($id == -1) {
            $id = Repositories\Users::findByEmail($nick);
        }
        if ($id == -1) {
            echo "Ce login n'existe pas";
            return;
        }

        /**
         * Avec cet ID, on récupère l'entité User
         * @var Entities\User $u
         */
        $u = Repositories\Users::retrieve($id);

        // Validate
        if ($u->validatePassword($password_clear) == false) {
            echo "Mot de passe incorrect";
            return;
        }

        // Créer une session

    }

}