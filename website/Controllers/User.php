<?php

namespace Controllers;

use Helpers\DisplayManager;
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
        $post = $req->getAllPOST();

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
                    http_response_code(400);
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
            http_response_code(400);
            echo "La confirmation n'est pas valide !";
            return;
        }

        /**
         * Check if an entity with the same nick exists
         * @var int $nickDuplicate
         */
        $count = (new \Queries\Users)
            ->filterByNick("=", $nick)
            ->filterByEmail("=", $email)// OR
            ->count();
        if ($count !== 0) {
            echo "A user with this nick and/or email already exists";
            return;
        }

        // Create the entity
        $u = new Entities\User();
        $u->setNick($nick);
        $u->setEmail($email);
        $u->setPasswordClear($password_clear);
        $u->setDisplay($display);
        $u->setPhone($phone);

        // Insert it
        try {
            (new \Queries\Users)->save($u);
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

    public static function postReinitialisation(\Entities\Request $req)
    {
        // On récupère les données
        $email = $req->getPOST('email');
        $u = (new \Queries\Users)->filterByEmail("=", $email)->findOne();
        // Si l'adresse mail n'existe pas on affiche la page d'erreur
        if ($u === null)
        {
            http_response_code(400);
            echo "Ce login n'existe pas";
            return;

        }

        //Là faut envoyer un mail avec un mdr provisoire random au type
        
       \Helpers\DisplayManager::redirectToController("User", "MajMdpReussie");
    }


    public static function postConnection(\Entities\Request $req): void
    {
        // Si l'usager est déjà connecté, le rediriger vers la page d'accueil
        if ($req->getUserID() !== null) {
            \Helpers\DisplayManager::redirectToController("User", "Informations");
            return;
        }

        // Récupérer les données
        $login = $req->getPOST('login');
        $password_clear = $req->getPOST('password');
        if (empty($login) || empty($password_clear)) {
            http_response_code(400);
            echo "Login et/ou mot de passe non spécifié";
            return;
        }

        /**
         * Vérifier que le nick et/ou e-mail existe et récupérer l'entité liée
         * @var int $id
         */
        $u = (new \Queries\Users)->filterByNick("=", $login)->findOne(); //trouve l'id lié au nickname
        if ($u === null) {
            $u = (new \Queries\Users)->filterByEmail("=", $login)->findOne();
        }
        if ($u === null) {
            http_response_code(400);
            echo "Ce login n'existe pas";
            return;
        }

        // Validate
        if ($u->verifyPassword($password_clear) == false) {
            echo "Mot de passe incorrect";
            return;
        }

        // Ajouter à la session et à la requête
        $_SESSION["user_id"] = $u->getID();
        $ok = $req->setUser($u);
        if (!$ok) {
            Error::getInternalError500($req);
            return;
        }

        \Helpers\DisplayManager::redirectToController("User", "Informations"); /* Redirection du navigateur */; // TODO: Le rediriger vers la page de sélection de propriété
    }

    public static function getConnectionPage(\Entities\Request $req): void
    {
        DisplayManager::display("connexion");
    }

    public static function getSubscriptionPage(\Entities\Request $req): void
    {
        DisplayManager::display("inscription");
    }

    public static function getInformations(\Entities\Request $req):void
    {
        $u = $req->getUser();
        if ($u === null) {
            http_response_code(403);
            return;
        }

        //On envoie vers la vue
        $data["user"] = $u;

        //Afficher
        \Helpers\DisplayManager::display("moncompte", $data);
    }

    public static function getResetPassword(\Entities\Request $req): void
    {
        DisplayManager::display("reset");
    }

    public static function getMajMdpReussie(\Entities\Request $req): void
    {
        DisplayManager::display("majmdpreussie");
    }


    //Mettre à jour les infos

    public static function postMAJInformations (\Entities\Request $req): void
    {
        $post= $req->getAllPOST();

        //On récupère des données
        $email = $post["email"];
        $newemail = $post["newemail"];
        $newphone = $post["nouveautel"];
        $mdp = $post["mdp"];

        //On récupère l'entité de l'utilisateur

        //On récupère l'entité user depuis la page de connexion
        $user = $req -> getUser();

        //MAJ de l'email si besoin
        if ((new \Queries\Users) -> filterByEmail("=", $email) -> findOne() != null)
        {
            DisplayManager::redirectToController(user , informations );
            return;
        }
        if ($email === $newemail AND $email != null) {
            $user->setEmail($email);
        }

        //MAJ du numero de tel
        if ($newphone != null) {
            $user->setPhone($newphone);
        }

        // Insertion de l'entité et de ses maj si le mdp de vérification est valide
        if ($user->verifyPassword($mdp) === false){
            return;
        }
        try {
            (new \Queries\Users) ->update($user);
        } catch (\Exception $e) {
            Error::getInternalError500($req);
            return;
        }
         \Helpers\DisplayManager::redirectToController(User, Informations );
    }

    //Changement de mdp
    public static function postMDP(\Entities\Request $req){

        $post = $req->getAllPOST();

        //On recupère les données
        $user = $req->getUser();

        //On récupère les infos après avoir vérifier qu'elles existent
        if (!isset($post["ancienmdp"]) OR !isset($post["nouveaumdp"]) OR !isset($post["cnouveaumdp"])){
            self::getInformations($req);
            return;
        }

        //On récupère les données
        $ancienmdp = $post["ancienmdp"];
        $newmdp = $post['nouveaumdp'];
        $cnewmdp = $post["cnouveaumdp"];

        //Vérification de l'ancien mdp
        if ($user->verifyPassword($ancienmdp) === false) {
            DisplayManager::display(user, informations );
            return;
        }

        if ($ancienmdp === $newmdp){
            DisplayManager::display(user, informations );
            return;
        }

        if ($newmdp !== $cnewmdp){
            DisplayManager::display(user, informations );
            return;
        }

        $user ->setPasswordClear($newmdp);

        // Insertion de l'entité et de ses maj
        try {
            (new \Queries\Users) -> update($user);
        } catch (\Exception $e) {
            Error::getInternalError500($req);
            return;
        }

        DisplayManager:: display("majmdpreussie");

    }

    /**
     * @param Entities\Request $req
     * @throws \Exception
     */
    public static function getSessionList(\Entities\Request $req): void
    {
        $user = $req->getUser();
        if ($user === null) {
            http_response_code(403);
            echo "Utilisateur non connecté, nous ne pouvons pas accéder à la page demandée";
            return;
        }

        // Retrieve sessions
        $sessions = (new \Queries\Sessions)
            ->filterByUser("=", $user)
            ->filterByCanceled(false)
            ->find();

        // Retrieve requests
        $requests_query = (new \Queries\Requests);
        foreach ($sessions as $session) {
            $requests_query->filterBySession("=", $session);
        }
        $requests = $requests_query->orderBy("started_processing", false)->find();
        foreach ($requests as $index => $request) {
            $requests[$request->getSessionID()] = $request;
            unset($requests[$index]);
        }

        // Publish data
        $data["sessions"] = $sessions;
        $data["requests"] = $requests;

        // Publish view
        DisplayManager::display("mysessions", $data);
    }

    public static function postSessionCancel(\Entities\Request $req): void
    {
        $user_id = $req->getUserID();
        if ($user_id === null) {
            http_response_code(403);
            echo "Utilisateur non connecté, nous ne pouvons pas accéder à la page demandée";
            return;
        }

        // Retrieve post parameter for session ids
        $session_ids = $req->getPOST("session_id");
        if (empty($session_ids)) {
            http_response_code(400);
            echo "Mauvaise requête: veuillez indiquer une session ID valide";
            return;
        }

        foreach ($session_ids as $session_id) {
            // Retrieve session
            $s = (new \Queries\Sessions)->retrieve($session_id);

            // Check for validity
            if ($s->getUserID() !== $req->getUserID()) {
                http_response_code(403);
                echo "Vous ne pouvez pas annuler la session de quelqu'un d'autre";
            }

            // Cancel it
            $s->setCanceled(true);

            // Push it
            (new \Queries\Sessions)->update($s);
        }

        // Return to session list
        self::getSessionList($req);
    }

    //Déconnexion
    public static function postDeconnexion (\Entities\Request $req){

        //On détruit la session
        session_destroy();

        //On redirige vers la page de connexion
        DisplayManager::redirectToController(User, ConnectionPage);
    }
}