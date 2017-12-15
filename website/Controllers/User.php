<?php

namespace Controllers;

use Helpers\DisplayManager;
use Repositories;
use Entities;

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
    public static function postJoin(array $get, array $post): void
    {
        // Check if the data exists
        $required = ["nick", "email", "password", "name", "surname", "phone"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
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
            echo "A user with this nick already exists";
            return;
        }

        /**
         * Check if an entity with the same email exists
         * @var int $emailDuplicate
         */
        $emailDuplicate = Repositories\Users::findByEmail($email) != null;
        if ($emailDuplicate) {
            echo "A user with this email already exists";
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
            echo "Error inserting user: " . $e;
        }

        // Include la page de confirmation
        $data = [
            "user" => $u,
        ];
        DisplayManager::display("dashboard",$data);
    }

    /**
     * Connexion
     */
    public static function postConnection(array $get, array $post): void
    {
        // Check if the data exists
        $required = ["login", "password"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
                return;
            }
        }

        // Récupérer les données
        $login = $_POST['login'];
        $password_clear = $_POST['password'];

        /**
         * Vérifier que le nick et/ou e-mail existe
         * @var int $id
         */
        $id = Repositories\Users::findByNick($login); //trouve l'id lié au nickname
        if ($id == -1) {
            $id = Repositories\Users::findByEmail($login);
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

        // Ajouter à la session
        $_SESSION["user_id"] = $u->getId();

        // Include la page de confirmation
        $data = [
            "user" => $u,
        ];
        DisplayManager::display("dashboard",$data);
    }

    public static function getConnectionPage(array $get, array $post): void
    {
        DisplayManager::display("connexion",array());
    }

    public static function getSubscriptionPage(array $get, array $post): void
    {
        DisplayManager::display("inscription", array());
    }

}