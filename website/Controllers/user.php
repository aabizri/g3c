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
     * join subscribes a user
     * @throws \Exception
     */
    public function join()
    {
        // Get the data
        $nick = $_POST["nick"];
        $email = $_POST["email"];
        $password_clear = $_POST["password"];
        $display = $_POST["name"] . " " . $_POST["surname"];
        $phone = $_POST["phone"];

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
        $u = new \Entities\User();
        $u->nick = $nick;
        $u->email = $email;
        $u->setPassword($password_clear);
        $u->display = $display;
        $u->phone = $phone;

        // Insert it
        try {
            Repositories\Users::insert($u);
        } catch (\Exception $e) {
            echo "Error inserting user" . $e;
        }
    }

    /**
     * Connexion
     */
    public function connexion()
    {
        // Récupérer les données
        $nick = $_POST['nick'];
        $password_clear = $_POST['password'];

        //Vérifier la présence du nick
        $id = \Repositories\Users::findByNick($nick); //trouve l'id lié au nickname
        if ($id == -1) {
            echo "CE LOGIN N'EXISTE PAS";
            return;
        }

        // Avec cet ID, on récupère l'entité User
        $u = \Repositories\Users::retrieve($id);

        // Validate
        if ($u->validatePassword($password_clear) == false) {
            echo "MOT DE PASSE INCORRECT";
            return;
        }

        // Créer une session

    }

    /**
     * @param $password
     * @return bool|void
     */
    public function modifiermdp(string $password)
    {
        //Récupérer les données
        $id = $_POST['id'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Avec cet ID, on récupère l'entité User
        $u = \Repositories\Users::retrieve($id);

        //Validate
        if ($u->validatePassword($current_password))
        {
            $u->setPassword($new_password);
        }
    }

    /**
     * @param string $nick
     */
    public function modifierinfo(string $nick, $birth_date, $phone, $email)

    {
        //Récupérer les données
        $id = $_POST['id'];
        $new_birth_date = $_POST['birth_date'];
        $birth_date = $_POST['birth_date'];
        $new_nick = $_POST['new_nick'];
        $nick = $_POST['nick'];
        $new_phone = $_POST['new_phone'];
        $phone = $_POST['phone'];
        $new_email = $_POST['new_email'];
        $email = $_POST['email'];

        //Avec cet ID, on récupère l'entité User
        $u = \Repositories\Users::retrieve($id);

        //Validate

        if (isset($nick))
        {
            $u->setNick($new_nick);
        }

        if (isset($birth_date))
        {
            $u->setBirthDate($new_birth_date);
        }

        if (isset($phone))
        {
            $u->setPhone($new_phone);
        }

        if (isset($email))
        {
            $u->setEmail($new_email);
        }



    }

}