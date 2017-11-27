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
        $u = new \Entities\User();
        $u->nick = $nick;
        $u->email = $email;
        $u->setPassword($password_clear);

        // Insert it
        try {
            \Repositories\Users::insert($u);
        } catch (\Exception $e) {
            echo "Error inserting user".$e;
        }
    }
        // Connexion
    public function connexion() {
        // Récupérer les données
        $nick = $_POST['nick'];
        $password_clear = $_POST['password'];

        //Vérifier la présence du nick
        $id = \Repositories\Users::findByNick($nick);
        if ($id==-1) {
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

}