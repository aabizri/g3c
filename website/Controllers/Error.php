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
    public static function getControllerNotFound404(\Entities\Request $req) {
        http_response_code(404);
        echo "Page inexistante, veuillez re-essayer avec une page valide";
        if ($req->getInDebug()) {
            echo "<br/> <strong> Informations de débuggage : </strong><br/>";
            echo "Controlleur demandé : ".$req->getController();
            echo "<br/>";
            echo "Méthode utilisée : ".$req->getMethod();
            echo "<br/>";
            echo "Action demandée : ".$req->getAction();
        }
    }

    public static function getInternalError500(\Entities\Request $req, \Throwable $t = null) {

        http_response_code(500);
        echo "Erreur Interne, veuillez nous excuser pour la gène occasionée";
        if ($req->getInDebug() && $t !== null) {
            echo "<br/> <strong> Informations de débuggage : </strong><br/><pre>";
            echo $t;
            echo "</pre";
        }
    }
}