<?php

namespace Helpers;

class Handler
{

    // Handle the request
    public static function handle(\Entities\Request $req)
    {
        // UTF8 Header
        header('Content-type: text/html; charset=utf-8');
        // Lancement de la temporisation
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

        // Store it in the request
        $req->setSession();

        // Vérification des valeurs catégorie / action
        $category = $req->getController();
        $action = $req->getAction();

        if (empty($category) || empty($action)) {
            \Controllers\Error::getInternalError500($req,new \Exception("Erreur: Ni la catégorie ni l'action est indiquée"));
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