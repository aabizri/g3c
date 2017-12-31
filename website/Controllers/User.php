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
    public static function postJoin(\Entities\Request $req): void
    {
        // Récupere le post
        $post = $req->getPOST();

        // Check if the data exists
        $required = ["nick", "email", "email_conf", "password", "password_conf", "name", "surname", "phone"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                echo "Missing key: " . $key;
                return;
            }
        }

        // Validation du recaptcha, seulement en cas de connection https
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                if (empty($post["g-recaptcha-response"])) {
                    echo "ERROR: Empty recaptcha";
                    return;
                }
                $response = $post["g-recaptcha-response"];
                $captcha = new \Helpers\ReCAPTCHA("", "6Le5Pz4UAAAAAK3tAgJ2sCG3SF8qz0zVeILYJiuo");
                $ok = $captcha->verify($response);
                if (!$ok) {
                    echo "Invalid captcha";
                    return;
                }
            }
        }

        // Assign values
        $nick = $post["nick"];
        $email = $post["email"];
        $email_conf = $post["email_conf"];
        $password_clear = $post["password"];
        $password_clear_conf = $post["password_conf"];
        $display = $post["name"] . " " . $_POST["surname"];
        $phone = $post["phone"];

        if (($email_conf != $email) || ($password_clear != $password_clear_conf)){
            echo "La confirmation n'est pas valide !";
            return;
        }

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
        DisplayManager::display("connexion",$data);
    }

    /**
     * Connexion
     */
    public static function postConnection(\Entities\Request $req): void
    {
        // Si l'usager est déjà connecté, le rediriger vers la page d'accueil
        if ($req->getUserID() !== null) {
            self::getConnectionPage($req);
            return;
        }

        // Récupère le post
        $post = $req->getPOST();

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
        if ($id == 0) {
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

        // Ajouter à la session et à la requête
        $_SESSION["user_id"] = $u->getId();
        $ok = $req->setUser($u);
        if (!$ok) {
            Error::getInternalError500($req);
            return;
        }

        // Include la page de confirmation
        $data = [
            "user" => $u,
        ];
        DisplayManager::display("dashboard", $data); // TODO: Le rediriger vers la page de sélection de propriété
    }

    //Afficher les infos du compte

    public static function getInformations (array $get, array $post): void
    {
        //POUR TESTER
        $session["user_id"] = 7;
        //On récupère l'id en SESSION depuis la page de connexion
        $user_id = $session["user_id"];
        //On recupère les données grace à cet id
        $user = \Repositories\Users::retrieve($user_id);

        //On envoie vers la vue
        $data["user"] = $user;

        //Afficher
        \Helpers\DisplayManager::display("moncompte", $data);
    }

    //Mettre à jour les infos

    public static function postMAJInformations (array $get, array $post): void
    {

        //On récupère des données
        $email = $post["email"];
        $cnewemail = $post["cnewemail"];
        $newaddress = $post["nouvelleaddresse"]; //AJOUTER L'ADDRESSE QUAND L'ABONNEMENT SERA FONCTIONNEL
        $newphone = $post["nouveautel"];
        $mdp = $post["mdp"];

        //On récupère l'entité de l'utilisateur
        //POUR TESTER
        $_SESSION["user_id"] = 7;

        //On récupère l'id en SESSION depuis la page de connexion
        $user_id = $_SESSION["user_id"];
        //$user_id->getUserID();

        //On recupère les données grace à cet id
        $user = \Repositories\Users::retrieve($user_id);

        //MAJ de l'email si besoin
        if (\Repositories\Users::findByEmail($email) != null) {
            echo "Cet email est déja lié à un compte";
            return;
        }
        if ($email === $cnewemail AND $email != null) {
            $user->setEmail($email);
        }

        //MAJ du numero de tel
        if ($newphone != null) {
            $user->setPhone($newphone);
        }

        // Insertion de l'entité et de ses maj si le mdp de vérification est valide
        if ($user->validatePassword($mdp) === true){
            try {
                Repositories\Users::push($user);
                echo "MAJ réussie";
            } catch (\Exception $e) {
                echo "Error inserting user: " . $e;
            }
        }
        else{
            echo "Mauvais mdp, modifications impossible";
            return;
        }
    }

    //Changement de mdp
    public static function setMDP(array $get, array $post){

        //POUR TESTER
        $session["user_id"] = 7;
        //On récupère l'id en SESSION depuis la page de connexion
        $user_id = $session["user_id"];
        //On recupère les données grace à cet id
        $user = \Repositories\Users::retrieve($user_id);

        //On récupère les infos
            $ancienmdp = $post["ancienmdp"];
            $newmdp = $post['nouveaumdp'];
            $cnewmdp = $post["cnouveaumdp"];

        //Vérification de l'ancien mdp
        if ($user->validatePassword($ancienmdp) === false AND $ancienmdp !== null) {
            echo "Mot de passe incorrect";
            return;
        }

        if ($ancienmdp === $newmdp){
            echo "L'ancien mdp ne peut pas être le nouveau";
            return;
        }

        if ($newmdp !== $cnewmdp){
            echo "Le mdp n'est pas confirmé";
            return;
        }

        $user ->setPassword($newmdp);
        echo "MAJ du mdp réussie";

        // Insertion de l'entité et de ses maj
        try {
            Repositories\Users::push($user);
        } catch (\Exception $e) {
            echo "Error inserting user: " . $e;
        }


    }


    public static function getConnectionPage(array $get, array $post): void
    public static function getConnectionPage(\Entities\Request $req): void
    {
        DisplayManager::display("connexion",array());
    }

    public static function getSubscriptionPage(\Entities\Request $req): void
    {
        DisplayManager::display("inscription", array());
    }

    public static function getAccountPage(\Entities\Request $req):void
    {
        $u = $req->getUser();
        if ($u === null) {
            http_response_code(403);
            echo "Utilisateur non connecté, nous ne pouvons pas accéder à la page moncompte";
            return;
        }

        DisplayManager::display("moncompte", array());
    }

}