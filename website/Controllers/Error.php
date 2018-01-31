<?php
/**
 * Created by PhpStorm.
 * User: aabizri
 * Date: 12/25/17
 * Time: 12:41 AM
 */

namespace Controllers;


class Error
{
    public static function getBadRequest400(\Entities\Request $req, $message = "")
    {
        http_response_code(400);
        echo "400 Mauvaise requête";
        if (!(empty($message))) {
            echo "<br/>" . $message;
        }
        if ($req->getInDebug()) {
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getForbidden403(\Entities\Request $req, $message = "")
    {
        http_response_code(403);
        echo "403 Forbidden/Interdit !";
        if (!(empty($message))) {
            echo "<br/>" . $message;
        }
        if ($req->getInDebug()) {
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getControllerNotFound404(\Entities\Request $req)
    {
        http_response_code(404);
        echo "Page inexistante, veuillez re-essayer avec une page valide";
        if ($req->getInDebug()) {
            echo "<br/> <h2> Informations de débuggage sur le routage : </h2><br/>";
            echo "Méthode utilisée : " . $req->getMethod();
            echo "<br/>";
            echo "Controlleur demandé : " . $req->getController();
            echo "<br/>";
            echo "Action demandée : " . $req->getAction();
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getInternalError500Throwables(\Entities\Request $req, \Throwable $t = null, string $message = "")
    {
        http_response_code(500);
        echo "Erreur Interne, veuillez nous excuser pour la gène occasionée";
        if ($req->getInDebug() && $t !== null) {
            echo "<br/> <h2> Informations de débuggage sur l'exception/l'erreur : </h2><br/>";
            if (!empty($message)) echo "<h3>Message : </h3>" . $message . "<br/>";
            echo "<h3>Exception/Erreur</h3><pre>\n" . $t . "\n</pre>";
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getInternalError500(\Entities\Request $req, string $message = "")
    {
        http_response_code(500);
        echo "Erreur Interne, veuillez nous excuser pour la gène occasionée";
        if ($req->getInDebug() && !empty($message)) {
            echo "<br/> <h2> Informations de débuggage sur l'exception/l'erreur : </h2><br/>";
            echo $message;
            echo "<br/> <h3> Informations de débuggage sur la requête : </h3><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }
}