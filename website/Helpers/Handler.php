<?php

namespace Helpers;

class Handler
{
    // Handle the request
    public static function handle(\Entities\Request $req)
    {
        // UTF8 Header
        header('Content-type: text/html; charset=utf-8');
        // Lancement de la temporisation (Niveau 0)
        ob_start(null, 0, PHP_OUTPUT_HANDLER_STDFLAGS);
        // Lancement de la temporisation (Niveau 1)
        ob_start("ob_gzhandler",0,PHP_OUTPUT_HANDLER_STDFLAGS);

        // Installation du handler de session
        session_set_save_handler(new \Helpers\SessionSaveHandler);

        // Start a session
        $sess_opt = [
            "name" => "LiveWellSessionID",
            //"cookie_secure" => true, // TANT QU'ON NE SERA PAS EN HTTPS NE PAS ACTIVER
            "cookie_lifetime" => \Helpers\SessionSaveHandler::lifetime * 60 * 60 * 24,
        ];
        session_start($sess_opt);
        $sess = \Repositories\Sessions::retrieve(session_id());
        if ($sess !== null && !$sess->isValid()) {
            session_regenerate_id();
        }

        // Store it in the request
        $req->setSession();

        // Si la session contient un user_id, on le met dans la requête
        if (!empty($_SESSION["user_id"])) {
            $req->setUserID($_SESSION["user_id"]);
        }

        // Vérifie que le User a le droit d'accéder à la propriété
        if ($req->getUserID() !== null && $req->getPropertyID() !== null) {
            if (\Repositories\Roles::findByUserAndProperty($req->getUserID(), $req->getPropertyID()) === null) {
                echo "L'utilisateur n'a pas de connexion à cette propriété, interdit !";
            }
        }

        // Vérification des valeurs catégorie / action
        $category = $req->getController();
        $action = $req->getAction();

        // Si l'usager va sur la page d'accueil (pas de controlleurs) et n'est pas connecté, il est redirigé vers la page de connection
        // S'il est connecté, il est redirigé vers la page de selection de propriété
        if (empty($category) && empty($action)) {
            if ($req->getUserID() === null) {
                $category = "User";
                $action = "ConnectionPage";
            } else {
                $category = "Property";
                $action = "Select";
            }
        } else if (empty($category) XOR empty($action)) {
            \Controllers\Error::getControllerNotFound404($req);
            return;
        }

        // Méthode
        $method = $req->getMethod();

        // Envoie au controlleur
        $call = self::controllerCall($category, $action, $method);

        // Si ça n'existe pas, appelle le controlleur 404
        if (empty($call)) {
            \Controllers\Error::getControllerNotFound404($req);
            return;
        }

        // Si ça existe, on appelle la fonction
        try {
            $call($req);
        } catch (\Throwable $t) {
            \Controllers\Error::getInternalError500($req,$t);
        }

        // Finalisation de la temporisation (Niveau 1)
        ob_end_flush();
        // Finalisation de la temporisation (Niveau 0)
        $response_length = ob_get_length();
        ob_end_flush();

        // Enregistrement dans la requête
        $req->setResponseLength($response_length);
    }

    /**
     * @param string $category
     * @param string $action
     * @return callable the function to be called, or null if there is none
     */
    private static function controllerCall(string $category, string $action, string $method): ?callable
    {
        // Get the fully qualified name of the class
        $classname = "\\Controllers\\" . $category;

        // Check that the class file exists
        if (!classFileExists($classname)) {
            return null;
        }

        // Check that the class exists
        // doc: https://secure.php.net/manual/fr/function.class-exists.php
        if (!class_exists($classname)) {
            return null;
        }

        // Get method name
        $methodname = strtolower($method) . $action;

        // Check that the method exists
        // doc: https://secure.php.net/manual/fr/function.method-exists.php
        if (!method_exists($classname, $methodname)) {
            return null;
        }

        // Set the callback
        $callback = [
            $classname,
            $methodname,
        ];

        return $callback;
    }
}