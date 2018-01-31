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

    public static function getJoin(\Entities\Request $req): void
    {
        DisplayManager::display("inscription");
    }

    /**
     * join subscribes a user
     */
    public static function postJoin(\Entities\Request $req): void
    {
        // Récupere le post
        $post = $req->getAllPOST();

        // Check if the data exists
        $required = ["nick", "email", "email_conf", "password", "password_conf", "name", "surname", "phone"];
        foreach ($required as $key) {
            if (empty($post[$key])) {
                Error::getBadRequest400($req, "Missing key: " . $key);
                return;
            }
        }

        // Validation du recaptcha, seulement en cas de connection https
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                // Récupération de la réponse (captcha)
                if (empty($post["g-recaptcha-response"])) {
                    Error::getBadRequest400($req, "Captcha absent");
                    return;
                }
                $response = $post["g-recaptcha-response"];

                // Création de l'objet captcha de validation de captcha
                $captcha = new \Helpers\ReCAPTCHA("", "6Le5Pz4UAAAAAK3tAgJ2sCG3SF8qz0zVeILYJiuo");

                // Vérification du captcha
                $ok = false;
                try {
                    $ok = $captcha->verify($response);
                } catch (\Throwable $t) {
                    Error::getInternalError500Throwables($req, $t, "Erreur lors de la vérification du captcha");
                    return;
                }
                if (!$ok) {
                    Error::getBadRequest400($req, "Captcha invalide");
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

        // Si une des valeurs de confirmation ne correspond pas, c'est une erreur
        if (($email_conf != $email) || ($password_clear != $password_clear_conf)) {
            Error::getBadRequest400($req, "La confirmation n'est pas valide !");
            return;
        }

        /**
         * Check if an entity with the same nick exists
         * @var int $nickDuplicate
         */
        $count = null;
        try {
            $count = (new \Queries\Users)
                ->filterByNick("=", $nick)
                ->filterByEmail("=", $email)// OR
                ->count();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de l'execution query de comptage d'utilisateurs partageant le même email/nick");
            return;
        }
        if ($count !== 0) {
            Error::getBadRequest400($req, "A user with this nick and/or email already exists");
            return;
        }

        // Create the entity
        try {
            $u = new Entities\User();
            $u->setNick($nick);
            $u->setEmail($email);
            $u->setPasswordClear($password_clear);
            $u->setDisplay($display);
            $u->setPhone($phone);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors du set des informations");
            return;
        }

        // Insert it
        try {
            (new \Queries\Users)->insert($u);
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de l'insertion du nouvel utilisateur");
            return;
        }

        // Include la page de confirmation
        $data = [
            "user" => $u,
        ];
        DisplayManager::display("connexion", $data);
    }

    public static function postLogin(\Entities\Request $req): void
    {
        // Si l'usager est déjà connecté, le rediriger vers la page d'accueil
        if ($req->getUserID() !== null) {
            try {
                \Helpers\DisplayManager::redirect302("properties");
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t,
                    "Erreur lors de la redirection de l'utilisateur déjà connecté");
            }
            return;
        }

        // Récupérer les données
        $login = $req->getPOST('login');
        $password_clear = $req->getPOST('password');
        if (empty($login) || empty($password_clear)) {
            Error::getBadRequest400($req, "Login et/ou mot de passe non spécifié");
            return;
        }

        // Récuper l'entité utilisateur
        $u = null;
        try {
            $u = (new \Queries\Users)
                ->filterByColumn("nick", "=", $login, "OR")
                ->filterByColumn("email", "=", $login, "OR")
                ->findOne();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la récupération de l'éntité utilisateur");
            return;
        }

        // Vérifier qu'il existe
        if ($u === null) {
            Error::getBadRequest400($req, "Ce login n'existe pas");
            return;
        }

        // Validate
        $password_ok = $u->verifyPassword($password_clear);
        if (!$password_ok) {
            Error::getForbidden403($req, "Mot de passe incorrect");
            return;
        }

        // Ajouter à la session et à la requête
        $_SESSION["user_id"] = $u->getID();
        $req->setUser($u);

        try {
            \Helpers\DisplayManager::redirect302("properties");
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la redirection de l'utilisateur");
        }
    }

    public static function getLogin(\Entities\Request $req): void
    {
        DisplayManager::display("connexion");
    }

    public static function getAccount(\Entities\Request $req): void
    {
        $u = $req->getUserID();
        if ($u === null) {
            Error::getBadRequest400($req, "Utilisateur non-connecté");
        }

        //On envoie vers la vue
        $data["user"] = $u;

        //Afficher
        \Helpers\DisplayManager::display("moncompte", $data);
    }

    //Mettre à jour les infos
    public static function postAccount(\Entities\Request $req): void
    {
        //On récupère des données
        $email = $req->getPOST("email");
        $email_conf = $req->getPOST("newemail");
        $newphone = $req->getPOST("nouveautel");
        $mdp = $req->getPOST("mdp");

        //On récupère l'entité user
        $user = $req->getUser();

        // Vérifie que le mdp donné est correct
        if ($user->verifyPassword($mdp) === false) {
            Error::getForbidden403($req, "Mod de passe incorrect");
        }

        // MAJ de l'email
        if ($email != null) {
            // Vérifier la confirmation
            if ($email !== $email_conf) {
                Error::getBadRequest400($req,
                    "La confirmation de l'email ne correspond pas à l'email indiqué");
                return;
            }

            // Vérification de non-dup d'email
            $email_dup = null;
            try {
                $number_of_users_with_this_email = (new \Queries\Users)->filterByEmail("=", $email)->count();
                $email_dup = $number_of_users_with_this_email !== 0;
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t,
                    "Erreur lors de la vérification de non-dup d'email");
                return;
            }

            // Si c'est un dup, erreur
            if ($email_dup) {
                Error::getBadRequest400($req, "Email déjà attribué à un autre utilisateur");
                return;
            }

            // Tentative de set l'email
            try {
                $user->setEmail($email);
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t, "Erreur lors du set de l'email");
            }
        }

        //MAJ du numero de tel
        if ($newphone != null) {
            try {
                $user->setPhone($newphone);
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t, "Erreur lors du set du tel");
            }
        }

        // Tente la MAJ de l'utilisateur
        try {
            (new \Queries\Users)->update($user);
        } catch (\Exception $e) {
            Error::getInternalError500Throwables($req);
            return;
        }

        // Redirige vers la page compte
        try {
            \Helpers\DisplayManager::redirect302("account");
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la redirection de l'utilisateur");
        }
    }

    //Changement de mdp
    public static function postAccountPassword(\Entities\Request $req)
    {
        //On recupère l'utilisateur
        $user = $req->getUser();

        // Unpack les données
        $ancienmdp = $req->getPOST("ancienmdp");
        $newmdp = $req->getPOST('nouveaumdp');
        $cnewmdp = $req->getPOST("cnouveaumdp");

        //On récupère les infos après avoir vérifier qu'elles existent
        if (empty($ancienmdp) || empty($newmdp) || empty($cnewmdp)) {
            Error::getBadRequest400($req, "Fields manquant dans le formulaire");
            return;
        }

        //Vérification de l'ancien mdp
        if ($user->verifyPassword($ancienmdp) === false) {
            Error::getForbidden403($req, "Ancien mot de passe incorrect");
            return;
        }

        // Vérification que l'ancien mdp et le nouveau ne sont pas les mêmes
        if ($ancienmdp === $newmdp) {
            \Helpers\DisplayManager::redirect302("account");
            return;
        }

        // Vérification de la confirmation du mdp
        if ($newmdp !== $cnewmdp) {
            Error::getBadRequest400($req, "Confirmation du mot de passe invalide");
            return;
        }

        // Set le password
        $user->setPasswordClear($newmdp);

        // Insertion de l'entité et de ses maj
        try {
            (new \Queries\Users)->update($user);
        } catch (\Exception $e) {
            Error::getInternalError500Throwables($req);
            return;
        }

        DisplayManager:: display("majmdpreussie");
    }

    /**
     * @param Entities\Request $req
     */
    public static function getSessions(\Entities\Request $req): void
    {
        $user = $req->getUser();
        if ($user === null) {
            Error::getForbidden403($req, "Utilisateur non connecté, nous ne pouvons pas accéder à la page demandée");
            return;
        }

        // Retrieve sessions
        $sessions = null;
        try {
            $sessions = (new \Queries\Sessions)
                ->filterByUser("=", $user)
                ->filterByCanceled(false)
                ->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, "Erreur lors de la récup des sessions");
            return;
        }

        // Retrieve requests
        $requests = null;
        try {
            $requests_query = new \Queries\Requests;
            foreach ($sessions as $session) {
                $requests_query->filterBySession("=", $session);
            }
            $requests = $requests_query->orderBy("started_processing", false)->find();
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupération des requêtes");
        }

        // Rekey requests by session ID
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

    public static function postSessionsCancel(\Entities\Request $req): void
    {
        $user_id = $req->getUserID();
        if ($user_id === null) {
            Error::getForbidden403($req, "Utilisateur non connecté, nous ne pouvons pas accéder à la page demandée");
            return;
        }

        // Retrieve post parameter for session ids
        $session_ids = $req->getPOST("session_id");
        if (empty($session_ids)) {
            Error::getBadRequest400($req, "Mauvaise requête: veuillez indiquer une session ID valide");
            return;
        }

        // récupérer les session à annuller et les annuller une par une
        foreach ($session_ids as $session_id) {
            // Retrieve session
            $s = null;
            try {
                $s = (new \Queries\Sessions)->retrieve($session_id);
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t, "Erreur lors de la récupération de la session");
                return;
            }

            // Check for validity
            if ($s->getUserID() !== $req->getUserID()) {
                Error::getForbidden403($req, "Vous ne pouvez pas annuler la session de quelqu'un d'autre");
                return;
            }

            // Cancel it
            $s->setCanceled(true);

            // Push it
            try {
                (new \Queries\Sessions)->update($s);
            } catch (\Throwable $t) {
                Error::getInternalError500Throwables($req, $t, "Erreur lors de la mise à jour de la session");
                return;
            }
        }

        // Return to session list
        try {
            \Helpers\DisplayManager::redirect302("account/sessions");
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la redirection de l'utilisateur");
        }
    }

    //Déconnexion
    public static function postLogout(\Entities\Request $req)
    {
        //On détruit la session
        session_destroy();

        //On redirige vers la page de connexion
        try {
            DisplayManager::redirect302("login");
        } catch (\Throwable $t) {
            Error::getInternalError500Throwables($req, $t,
                "Erreur lors de la redirection de l'utilisateur");
        }
    }

    //Supprimer un utilisateur
    public static function postDeleteUser(\Entities\Request $req)
    {

        //Récupérer user id
        $user_id = $req->getUserID();

        //On supprime les roles et le user id
        (new \Queries\Roles)
            ->filterByUserID("=", $user_id)
            ->delete();

        (new \Queries\Users)
            ->filterByColumn("id", "=", $user_id, "AND")
            ->delete();

        DisplayManager::redirect303("login");
    }
}