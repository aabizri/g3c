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
            echo "<br/> <strong> Informations de débuggage sur la requête : </strong><br/>";
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
            echo "<br/> <strong> Informations de débuggage sur la requête : </strong><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getControllerNotFound404(\Entities\Request $req) {
        http_response_code(404);
        echo "Page inexistante, veuillez re-essayer avec une page valide";
        if ($req->getInDebug()) {
            echo "<br/> <strong> Informations de débuggage sur le routage : </strong><br/>";
            echo "Méthode utilisée : ".$req->getMethod();
            echo "<br/>";
            echo "Controlleur demandé : " . $req->getController();
            echo "<br/>";
            echo "Action demandée : ".$req->getAction();
            echo "<br/> <strong> Informations de débuggage sur la requête : </strong><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }

    public static function getInternalError500Throwables(\Entities\Request $req, \Throwable $t = null)
    {
        http_response_code(500);
        echo "Erreur Interne, veuillez nous excuser pour la gène occasionée";
        if ($req->getInDebug() && $t !== null) {
            echo "<br/> <strong> Informations de débuggage sur l'exception/l'erreur : </strong><br/><pre>";
            echo $t;
            echo "</pre>";
            echo "<br/> <strong> Informations de débuggage sur la requête : </strong><br/>";
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
            echo "<br/> <strong> Informations de débuggage sur l'exception/l'erreur : </strong><br/><pre>";
            echo $message;
            echo "</pre>";
            echo "<br/> <strong> Informations de débuggage sur la requête : </strong><br/>";
            echo "<pre>";
            echo $req->prettyPrint();
            echo "</pre>";
        }
    }
}